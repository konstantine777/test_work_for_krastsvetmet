<?php

namespace controllers;
use prototypes\Controller;
use prototypes\View;

class CheckController extends Controller
{

	public function urlAction($data)
	{

		if(isset($data['url']) AND is_string($data['url']))
		{

			$url = json_decode($data['url']);
			$space_separated = count(explode(' ' ,$url));

			if($space_separated === 1 AND $space_separated[0] !== '')
			{


				if(@get_headers($url))
				{

					echo json_encode(["answer" => true]);

				}
				else echo json_encode(["answer" => false]);

			}
			else echo json_encode(["answer" => false]);

		}
		else View::show_error('Нет данных или они не представляют строку.');

	}

	public function selfAction($data)
	{

		if(isset($data['ref']))
		{

			$str = json_decode($data['ref']);

			if(!is_string($str) OR $str === 'css' OR $str === 'js')
			{
				echo json_encode(['answer' => false]);
				die();
			}

			$alias = $this->model->checkAliasByRef($str);

			if(isset($alias['id']))
			{

				echo json_encode(['answer' => false]);
				die();

			}

			echo json_encode(['answer' => true]);

		}

	}

}