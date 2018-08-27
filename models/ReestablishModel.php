<?php

namespace models;
use prototypes\Model;
use prototypes\View;


class ReestablishModel extends Model
{

	public function synchronize($user_id, $union, $key)
	{

		session_start(); // Открываем сессию.

		// Получм данные пользователя по его уникальному ключу.
		$recoverableUser = $this->getUserByKey($key);

		// Если получили пустой массив, то пользователя такого не существует, возможно, был удален.
		if(count($recoverableUser) === 0) View::show_error('Пользователя с данным ключем не найдено');

		// Вытащим из массива идентификатор востанавливаемого пользователя.
		$recoverableUserID = $recoverableUser['id'];

		// Проверим, не попытка ли это восстановить самого себя.
		if($user_id === $recoverableUserID) View::show_error('Попытка обновить самого себя');


		// Узнаем, стоит ли объединить данные пользователей.
		if($union)
		{

			// Если надо, поменяем идентификаторы текущего пользователя на идентификаторы восстановливаемого
			// во всех пренадлижащих текущему пользователю псевдонимах.
			if($this->database->query("UPDATE `users_pseudonym` SET `master_id` = '$recoverableUserID' WHERE `master_id` = '$user_id'"))
			{

				// Хатем удалим текущего пользователя.
				$this->deleteUserById($user_id);

			}
			else View::mysql_errors('Ошибка при слиянии данных пользователей', $this->database->errno, $this->database->error);

		}

		// Если операции с объединением пользователя не понадобились,
		// перейдем сразу сюда и запишем хэш в сессию.
		$_SESSION['user'] = $recoverableUser['hash'];

		// Вернем пользователю его новый массив псевдонимов.
		return $this->getAliases($recoverableUserID);

	}

}