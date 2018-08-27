<?php

namespace redirect;
use prototypes\Model;
use prototypes\View;

class Redirect extends Model
{

	/**
	 * Redirect constructor.
	 * @param $user
	 * @param $href
	 * Конструктор принимает два параметра, ссылку перехода href и массив с id
	 * пользователя осуществляющего переход.
	 */
	function __construct($user, $href)
	{

		parent::__construct();

		// Проверим существует ли данная ссылка в базах данных, и получим информацию
		// о ней в случае успеха.
		$href_data = $this->checkReference($href);

		// Зная данные ссылки, перенаправим пользователя соответствующим способом,
		// который пределяется в методе redirect.
		$this->redirect($href_data, $user);

	}

	public function checkReference($href)
	{

		/**
		 * И так, мы получили ссылку перехода, надо обратиться в базы,
		 * для получения информации о этой ссылке.
		 */
		if($hrefId = $this->database->query("SELECT `id`, `best_before`, `is_commercial`, `is_pseudonym_of` FROM `users_pseudonym` WHERE `short_url`='$href'"))
		{

			// Обычное получение массива из SQL троки, пришедшей в ответе.
			$resultArray = $this->getArrayFromSQL($hrefId);

			// Если в прешедшем результате имеется id, то все прошло успешно.
			// В противном случае, уведомим пользователя, о несуществующей ссылке.
			if(isset($resultArray['id']))
			{


				/**
				 * Следующая проверка, на время действия ссылки,
				 * если оно бесконечно (равно infinity), то сразу
				 * вернем массив информации о ссылке, в противном случае
				 * проверим, не вышло ли время действия ссылки,
				 * если вышло, уведомим об этом переходящего пользователя.
				 */
				if($resultArray['best_before'] !== 'infinity')
				{

					// Приведем время к общему виду, для сравнения и получим текущее.
					$termOfHref = strtotime($resultArray['best_before']);
					$now = $this->date->getTimestamp() + 14400;

					// Сравним их.
					if($now < $termOfHref)
					{

						// Если текущее меньше заданного, то все хорошо, вернем массив данных.
						return $resultArray;

					}
					else View::show_message('Ссылка просрочена', 'Данная ссылка, уже не действительна. Обратитесь к источнику.'); // Если текущее больше заданного, уведомим о просроченной ссылке.

				}
				else return $resultArray;

			}
			else View::show_message('Ошибка 404', 'Запрашиваемая ссылка не найдена. Убедитесь, что не допустили ошибку.'); // Ссылка не найдена

		}
		else View::mysql_errors('Ошибка при попытке получить данные ссылки', $this->database->errno, $this->database->error);

	}

	private function redirect($data, $user)
	{

		/**
		 * Данные к нам приходят в виде массивов, но
		 * нам нужны конкретные значения,
		 * поэтому передадим их в переменные.
		 */
		$id_ref = $data['id']; // id ссылки-псевдонима в базах.
		$id_user = $user['id']; // id переходящего пользователя.
		$true_href_id = $data['is_pseudonym_of']; // id истинной ссылки.


		/**
		 * И так, если мы дошли сюда, не получив не единой ошибки,
		 * то смело можем записывать посетителя в бд.
		 */
		if($this->database->query("INSERT INTO `visitors` (visit_pseudonym_id, user_id) VALUES ('$id_ref', '$id_user')"))
		{

			// Теперь получим истенную ссылку по переданному id.
			if($true_href_sql = $this->database->query("SELECT `url` FROM `full_url` WHERE `id`='$true_href_id'"))
			{

				// Обычное получение массива из SQL троки, пришедшей в ответе.
				$true_href_array = $this->getArrayFromSQL($true_href_sql);

				/**
				 * Далее посмотрим, комерческая ли у нас ссылка,
				 * если да, то выведем заставку, с 5 секундной задержкой
				 * перед переходом, в противном случае, сразу же перенаправим.
				 */
				if($data['is_commercial'])
				{

					// Назаначим переадрисацию с задержкой.
					header('Refresh: 5; url='.$true_href_array['url']);

					// Получим все пути возможных вариантов заставок.
					$files = glob(ROOT_SITE . "pages/show_to_commercial/*");

					// Случайным образом выберем любую.
					$rand = rand(0, count($files) - 1);

					// Выведем.
					View::show_pattern($files[$rand]);

				}
				else
				{

					// Либо сразу перенаправим, для тех, у кого не хватило денег.
					header('Location:' . $true_href_array['url']);

				}

			}
			else View::mysql_errors('Ошибка при попытке получить полную ссылку перехода', $this->database->errno, $this->database->error);

		}
		else View::mysql_errors('Ошибка при попытке записать данные посетителя', $this->database->errno, $this->database->error);

	}

}