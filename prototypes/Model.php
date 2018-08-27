<?php

namespace prototypes;


class Model
{

	protected $database;
	protected $date;

	/**
	 * Model constructor.
	 * В конструкторе объявим интересующие нас объеты,
	 * с целью наследовать этот класс, там, где они могут понадобиться.
	 */
	function __construct()
	{

		$this->database = new \mysqli(DB_DATA['host'], DB_DATA['username'], DB_DATA['password'], DB_DATA['db_name']);
		$this->date = new \DateTime();
		$this->date->setTimezone(new \DateTimeZone('Asia/Krasnoyarsk'));

	}

	// Простенький метод, там, где требуется получить всегда первый элемент
	// массива прешедшего с sql апросом.
	protected function getArrayFromSQL($result)
	{

		$result->data_seek(0);
		$array = $result->fetch_assoc();

		return $array;

	}

	// Регистрирует пользователя, используется только в файле роутере.
	public function registerUser($request)
	{

		// Дважды зашифруем строу времени в формате Unix.
		$hash = md5(md5(time()));
		// Проверим, существует ли такой пользователь, т.к, существует вероятность
		// сбоя времени на сервере и образования одинаковых хешей.
		$result = $this->getUser($hash);
		// Сгенерируем код для восстановления пользователя.
		$unique_code = createRandomKey(3, 4);

		// Проверим наличие хеша.
		if(isset($result['id']))
		{
			// Если он есть, перенаправим его сюда же, для повторной регистрации, зная строку запроса.
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$request);

		}
		else
		{

			// Если же его нет, запишим его в таблицу пользователей и вернем хеш для записи в сессии.
			if($this->database->query("INSERT INTO `users` (hash, unique_code) VALUES ('$hash', '$unique_code')"))
			{

				return $hash;

			}
			else View::mysql_errors('Ошибка запроса при попытке записи', $this->database->errno, $this->database->error);

		}

		return false;

	}

	/**
	 * @param $hash
	 * @return mixed
	 * Возвращает идентификатор пользователя по хешу,
	 * целесообразен при проверках.
	 */
	public function getUser($hash)
	{

		if($answer = $this->database->query("SELECT `id` from `users` WHERE `hash`='$hash'"))
		{

			return $this->getArrayFromSQL($answer);

		}

		View::mysql_errors('Ошибка при записи в базу данных', $this->database->errno, $this->database->error);

	}

	// Получаем данные пользователя по уникальному ключу.
	public function getUserByKey($key)
	{

		if($answer = $this->database->query("SELECT * from `users` WHERE `unique_code`='$key'"))
		{

			return $this->getArrayFromSQL($answer);

		}

		View::mysql_errors('Ошибка при записи в базу данных при получении данных пользователя по уникальному ключу', $this->database->errno, $this->database->error);

	}

	// Удаляем пользователя по идентификатору.
	public function deleteUserById($id)
	{

		if($answer = $this->database->query("DELETE FROM `users` WHERE `id`='$id'"))
		{

			return true;

		}

		View::mysql_errors('Ошибка при записи в базу данных при получении данных пользователя по уникальному ключу', $this->database->errno, $this->database->error);


	}

	// Возвращает псевдоним по его идентификатору.
	public function checkAliasById($id)
	{

		if($answer = $this->database->query("SELECT `short_url` from `users_pseudonym` WHERE `id`='$id'"))
		{

			return $this->getArrayFromSQL($answer);

		}

		View::mysql_errors('Ошибка при получении псевдонима', $this->database->errno, $this->database->error);


	}

	// Возвращает идентификатор псевдонима, по его ссылке.
	public function checkAliasByRef($ref)
	{

		if($answer = $this->database->query("SELECT `id` from `users_pseudonym` WHERE `short_url`='$ref'"))
		{

			return $this->getArrayFromSQL($answer);

		}

		View::mysql_errors('Ошибка при получении id псевдонима', $this->database->errno, $this->database->error);


	}

	// Возвращает мдентификатор url дресса, по его его ссылке.
	// Используется для проверки занятости url.
	public function checkOriginalHref($href)
	{

		if($url = $this->database->query("SELECT `id` FROM `full_url` WHERE `url`='$href'"))
		{

			return $this->getArrayFromSQL($url);

		}

		View::mysql_errors('Ошибка при получении id оригинальной ссылки', $this->database->errno, $this->database->error);

	}

	// Возвращает url дресс по идентификатору псевдонима привязанного к нему
	public function getOriginalHrefFromAliasId($id)
	{

		$query = "SELECT `url` FROM `full_url` WHERE `id` = (SELECT `is_pseudonym_of` FROM `users_pseudonym` WHERE `id`='$id')";

		if($result = $this->database->query($query))
		{

			return $this->getArrayFromSQL($result);

		}

		View::mysql_errors('Ошибка при получении ригинальной ссылки', $this->database->errno, $this->database->error);

	}

	// Проверяет на существование сайт, запрашивая заголовки у него.
	// Возвращает true в случае удачного их получени и false в случае провала.
	public function checkReference($link)
	{

		if(preg_match("#^https?://.+#", $link))
		{

			return true;

		}

		return false;

	}

	// Проверяет, является ли пользователь, чей идентификатор был передан,
	// создателем псевдонима, чей идентификатор так же передан.
	public function verifyUserRights($id, $aliasId)
	{

		$query = "SELECT `master_id` FROM `users_pseudonym` WHERE `id`='$aliasId'";

		if($result = $this->database->query($query))
		{

			$data = $this->getArrayFromSQL($result);

			if($data['master_id'] === $id) return true;

			return false;

		}

		View::mysql_errors('Ошибка при получении ригинальной ссылки', $this->database->errno, $this->database->error);
	}

	/**
	 * @param $user_id
	 * @return array
	 * Несмотря на простоту задачи, этот метод имеет не самый простой
	 * sql запрос, но вместе с тем, показывает всю гибкость этого предмета.
	 */
	public function getAliases($user_id)
	{

		/**
		 * Здесь мы получаем все нужные нам поля из пренадлежащих пользователю
		 * псевдонимов, но попутно мы получаем и их url на которые они перенаправляют,
		 * и связаны с ним полем is_pseudonym_of таблицы users_pseudonym.
		 */
		$query = "SELECT users_pseudonym.id, users_pseudonym.short_url, users_pseudonym.best_before, users_pseudonym.is_commercial, full_url.url 
					FROM `full_url` 
					JOIN `users_pseudonym` 
					ON users_pseudonym.is_pseudonym_of = full_url.id 
					AND users_pseudonym.master_id = '$user_id'";

		if($aliases = $this->database->query($query))
		{

			// Получив ответ, сделаем из него массив, со всеми влючающими элементами.
			// В данном случае, вложенность нам подходит, ничего больше делать не надо.
			$result = $aliases->fetch_all(1);

			for ($i = 0; $i < count($result); $i++)
			{

				$result[$i]['domain'] = $_SERVER['HTTP_HOST'].'/';

			}

			$unique_key = $this->getUserUniqueKey($user_id);

			// Возвращаем массив.
			return [
				"aliases" => $result,
				"unique_key" => $unique_key['unique_code']
			];

		}

		View::mysql_errors('Ошибка при получении данных пользовательского псевдонима: ', $this->database->errno, $this->database->error);

	}

	public function getUserUniqueKey($id)
	{

		$query = "SELECT `unique_code` FROM `users` WHERE `id`='$id'";

		if($result = $this->database->query($query))
		{

			return $this->getArrayFromSQL($result);

		}

		View::mysql_errors('Ошибка при получении уникального ключа', $this->database->errno, $this->database->error);


	}

	// Деструктор закрывает соединение с бд, перед завершением скрипта.
	function __destruct()
	{

		$this->database->close();

	}

}