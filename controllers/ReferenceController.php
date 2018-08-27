<?php

namespace controllers;
use prototypes\Controller;
use prototypes\View;


class ReferenceController extends Controller
{

	/**
	 * @param $data
	 * @param $user
	 	{
			link: string,
	 		rand_alias: bool,
	 		self_alias: string,
			duration: string,
			commercial_link: bool
		}
	 */

	public function addAction($data, $user)
	{

		// Проверим, пришли ли все необходимые переменные.
		if(isset($data['link']) AND isset($data['rand_alias']) AND isset($data['self_alias']) AND isset($data['duration']) AND isset($data['commercial_link']))
		{

			// Убедившись в их наличии, раскодируем их.
			$link = json_decode($data['link']);
			$rand_alias = json_decode($data['rand_alias']);
			$self_alias = json_decode($data['self_alias']);
			$duration = json_decode($data['duration']);
			$commercial_link = json_decode($data['commercial_link']);

			// Далее, проверим на типы данных, совпадают ли они с необходимыми.
			if(is_string($link) AND is_bool($rand_alias) AND is_string($self_alias) AND is_string($duration) AND is_bool($commercial_link))
			{

				// Проверим логику.
				// При генирации случайной ссылки, выбор лисной ссылки должен быть не доступен.
				if($rand_alias AND $self_alias !== '') View::show_error('Алеас не может быть одновременно собственным и случайным');;
				// И наоборот, если выбранна собственная ссылка, она не должна быть пустой.
				if(!$rand_alias AND $self_alias === '') View::show_error('Выбран собственный псевдоним, но не назначено имя');;
				// Проверим, представляет ли входная строка дату и время.
				if(!strtotime($duration) AND $duration !== 'infinity') View::show_error('Переданная строка не является представлением даты и времени ');;

				// Проверим, существует ли предложенный сайт.
				if ($this->model->checkReference($link))
				{

					// Выберем один из двух путей, генирация случайной ссылки или установка собственной.
					if($rand_alias)
					{

						// Создадим массив для передачи.
						$allData = [
							"link" => getNormalizeAddressString($link),
							"alias" => createRandomKey(),
							"duration" => $duration,
							"commercial_link" => $commercial_link
						];

						// Отправляем.
						$createdAlias = $this->model->addNewAlias($allData, $user);

					}
					else
					{
						// Если ключ не случайный, проверим, не занят ли он, на случай, если
						//кто то решил обойти запреты клиентской части.
						$isAlias = $this->model->checkAliasByRef($self_alias);

						// Псевдоним не занят? Тогда продолжим.
						if(!isset($isAlias['id']))
						{

							// Создадим массив для отправки.
							$allData = [
								"link" => getNormalizeAddressString($link),
								"alias" => $self_alias,
								"duration" => $duration,
								"commercial_link" => $commercial_link
							];

							// Отправляем.
							$createdAlias = $this->model->addNewAlias($allData, $user);

						}
						else View::show_error('Запрошенное имя псевдонима уже занято');
						// Не вышел не один из вариантов? Значит нас пытались надуть.

					}

					// Отправим клиенту ответ на запрос.
					echo json_encode($createdAlias);

				}
				else View::show_error('Переданного сайта не существует');

			}
			else View::show_error('Какаие то из переменных не соответствуют необходимым типам данных');

		}
		else View::show_error('Отсутствует одна из необходимых переменных');

	}

	/**
	 * @param $date
	 * @param $user
		{
			alias: int
		}
	 */
	public function statisticAction($date, $user)
	{

		// Проверим, существует ли необходимая нам переменная.
		if(isset($date['alias']))
		{

			// В случае успеха раскодируем ее.
			$aliasId = json_decode($date['alias']);
			$userId = $user['id'];

			// Проверим, является ли она целым числом, как нам и нужно.
			if(is_int($aliasId))
			{

				// Проверим права входящего, на право получить информацию о статистике данной ссылки.
				if($this->model->verifyUserRights($userId, $aliasId))
				{

					// Получим псевдоним и ссылку, на которую он перенаправляет
					// для проверки и дальнейшей записи в ответе.
					$alias = $this->model->checkAliasById($aliasId);
					$original = $this->model->getOriginalHrefFromAliasId($aliasId);

					// Проверим их наличие, если нет псевдонима, запрос ложный, вероятнее всего со стороны.
					if(isset($alias['short_url']) AND isset($original['url']))
					{

						// В случае успеха, получим статистику по псевдониму.
						$aliasStatistic = $this->model->getAliasStatistic($aliasId);

						// А так же статистику по ссылке его представляющую.
						$originalHrefStatistic = $this->model->getOriginalHrefStatistic($aliasId);

						// Соберем все в один массив.
						$answer = ["alias" => $aliasStatistic, "original" => $originalHrefStatistic];

						// Отправим.
						echo json_encode($answer);

					}
					else View::show_error('Такого псевдонима не существует');

				}
				else View::show_error('У вас нет прав на данные этого псевдонима');

			}
			else View::show_error('Идентификатор псевдонима, не является целым числом');

		}
		else View::show_error('Не передан идентификатор псевдонима');

	}

	/**
	 * @param $data
	 * @param $user
	 	{
			void
	 	}
	 */
	public function aliasesAction($data, $user)
	{

		// Идентификатор пользователя к нам приходит из файла роутера
		// Если дело дошло сюда, значит проверка пройдена,
		// никаких лишних телодвежений.
		$userId = $user['id'];

		// Получим все псевдонимы, которые имеет пользователь.
		$aliases = $this->model->getAliases($userId);

		// Отправим их.
		echo json_encode($aliases);

	}

}