<?php

namespace controllers;
use prototypes\Controller;
use prototypes\View;


class ReestablishController extends Controller
{

	/**
	 * @param $data
	 * @param $user
	 * Принимает на вход два параметра, уникальный ключ пользователя,
	 * к которому нужно получить доступ и флаг, указывающий, надо ли
	 * объеденить две записи в одну.
		{
			key: string,
			combine: bool
		}
	 */
	public function reestablishAction($data, $user)
	{

		// Проверим наличие данных.
		if(isset($data['key']) AND isset($data['combine']))
		{

			// Раскодируем из.
			$key = json_decode($data['key']);
			$combine = json_decode($data['combine']);
			$userID = $user['id'];

			// Проверим, соответствуют ли данные нашим ожиданиям.
			if(is_string($key) AND is_bool($combine) AND strlen($key) === 14)
			{

				$recoverableUser = $this->model->getUserByKey($key);

				if(count($recoverableUser) === 0 || $userID == $recoverableUser['id'])
				{

					$self_aliases = $this->model->getAliases($userID);

					echo json_encode([
						'success' => false,
						"aliases" => $self_aliases
					]);

					die();

				}

				// Если все прошло успешно, отправляем на обработку в модель.
				$newAliases = $this->model->synchronize($userID, $combine, $key);

				// Возвращаем новый массив пользователю.
				echo json_encode([
					"success" => true,
					"aliases" => $newAliases
				]);

			}
			else View::show_error('Какое то из переданных значений не соответствует ожидаемым параметрам.');


		}
		else View::show_error('Не переданны нужные параметры.');

	}

}