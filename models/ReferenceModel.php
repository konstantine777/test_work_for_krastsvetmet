<?php

namespace models;
use prototypes\Model;
use prototypes\View;


class ReferenceModel extends Model
{

	/**
	 * @param $data
	 * @param $user
	 * @return array
	 * Метод создает новый псевдоним, возвращая массив с менем для позьзователя и
	 * и временем действия, что бы можно было нарисовать это на клиенте.
	 */
	public function addNewAlias($data, $user)
	{

		/**
		 * Деструктурируем массив, от связки compact +  extract было решено отказаться,
		 * ввиду их неочевидности для прямого использования в sql запросах.
		 */
		$link = $data['link'];
		$alias = $data['alias'];
		$duration = $data['duration'];
		$commercial_link = $data['commercial_link'];

		$user_id = $user['id'];

		// Проверим, есть ли в наших базах url сайта, на который должен перенаправлять псевдоним.
		$resultRefRequest = $this->checkOriginalHref($link);
		// Деструктурируем, для упрощения. Не лучшее решение. Уже нет времени исправлять.
		$hrefId = (isset($resultRefRequest['id'])) ? $resultRefRequest['id'] : '';

		// Проверим, есть ли у нас url.
		if(!isset($resultRefRequest['id']))
		{

			// Если нет, добавим его в базу данных, в которой хранятся все url.
			if($this->database->query("INSERT INTO `full_url` SET `url`='$link'"))
			{

				// И перейдем еще раз в данный метод, но уже с сзаписанным url в таблице.
				return $this->addNewAlias($data, $user);

			}
			else View::mysql_errors('Ошибка при записи оригинальной ссылки', $this->database->errno, $this->database->error);

		}
		elseif($this->database->query("INSERT INTO `users_pseudonym` (master_id, short_url, is_pseudonym_of, best_before, is_commercial) VALUES ('$user_id', '$alias', '$hrefId', '$duration', '$commercial_link')"))
		{

			// Получим уникальный код.
			$key = $this->getUserUniqueKey($user['id']);

			// Определим протокол нашего сервера.
			if (isset($_SERVER['HTTPS']))
				$scheme = $_SERVER['HTTPS'];
			else
				$scheme = '';
			if (($scheme) && ($scheme != 'off')) $scheme = 'https';
			else $scheme = 'http';

			// Усли url есть, сделаем новую запись в таблице с псевдонимами, привязав псевдоний к url.
			// И вернем результат в виде массива с псевдонимом и сроком службы.
			return [
				"alias" => $scheme.'://'.$_SERVER['HTTP_HOST'].'/'.$alias,
				"duration" => $duration,
				"unique_key" => $key['unique_code']
			];

		}
		else View::mysql_errors('Ошибка записи в нового псевданима', $this->database->errno, $this->database->error);

	}

	/**
	 * @param $id
	 * @return array
	 * Здесь можно получить статистку, по запрошенному id,
	 * разумеется, если соблюдены права доступа, конечно.
	 */
	public function getAliasStatistic($id)
	{

		// Получим всех пользователей, которые переходили по данной ссылке.
		if($statistic = $this->database->query("SELECT `user_id` FROM `visitors` WHERE `visit_pseudonym_id`=$id"))
		{

			// Уберем не нужную вложенность. Получим все посещения.
			$arrayVisit = remove_nesting($statistic->fetch_all());

			// Уберем повторения из массива. Получим уникальных посетителей.
			$arrayUniqueVisit = array_unique($arrayVisit);
			// При помощи array_count_values можно подсчитать, сколько раз заходил каждый пользователь.
			// Это о возможностях расширения функционала.

			// Получим сам алиас.
			$alias_string = $this->checkAliasById($id);

			// Посчитаем количиство из каждого массива и вернем значения в виде массива.
			return [
				"total_conversions" => count($arrayVisit),
				"unique_conversions" => count($arrayUniqueVisit),
				"alias" => $_SERVER['HTTP_HOST'] . '/' . $alias_string['short_url']
			];

		}
		else View::mysql_errors('Ошибка при получении статистики псевдонима', $this->database->errno, $this->database->error);

	}

	/**
	 * @param $aliasId
	 * @return array
	 * Аналогичный вышестоящему метод, за одним исключением, в отличии от предыдущего,
	 * он находит посещения для конкретного url из всех связанных с ним псевдонимов.
	 */
	public function getOriginalHrefStatistic($aliasId)
	{

		// Строка с подзапросом. Находит все псевдонимы, которые связанны с url, к которому привязан данный псевдоним.
		$query = "SELECT `id` FROM `users_pseudonym` WHERE `is_pseudonym_of` = (SELECT `is_pseudonym_of` FROM `users_pseudonym` WHERE `id` = '$aliasId')";

		if($alias_of_href = $this->database->query($query))
		{

			/**
			 * На выходе пришел список идентификаторов, но не все так просто,
			 * он представляет из себя массив, каждый элемент которого представляет
			 * массив, с единственным ключем id  его значением, нам не нужна такая вложенность,
			 * поэтому уберем ее специально для этого написсаной функцией remove_nesting.
			 */
			$id_list = remove_nesting($alias_of_href->fetch_all());

			// Получим всех пользователей, которые переходили по псевдонимам, идентификаторы которых мы получили.
			if($visitors = $this->database->query("SELECT `user_id` FROM `visitors` WHERE `visit_pseudonym_id` IN (" . implode(',', $id_list) . ")"))
			{

				// Снова уберем вложенность. И получим все переходы.
				$result_visitors = remove_nesting($visitors->fetch_all());
				// Уберем совпадения, получим уникальные переходы.
				$unique_visitors = array_unique($result_visitors);

				$href = $this->getOriginalHrefFromAliasId($aliasId);

				// Соберем все в общий массив и вернем.
				return [
					"total_conversions" => count($result_visitors),
					"unique_conversions" => count($unique_visitors),
					"url" => $href['url']
				];

			}
			else View::mysql_errors('Ошибка при получении позователей, посетивших оригинальную ссылку', $this->database->errno, $this->database->error);

		}
		else View::mysql_errors('Ошибка при получении id псевдонимов ссылки', $this->database->errno, $this->database->error);

	}

}