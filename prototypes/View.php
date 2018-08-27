<?php

namespace prototypes;


class View
{

	// При переходе в корень, загружает основную страницу.
	public static function root($domain, $debag = null)
	{

		require_once ROOT_SITE . 'pages/root.php';

		die();

	}

	// Хагружает извещение, о не найденной странице.
	// Но мы то с вами знаем, что их и нет на самом деле :)
	public static function show_message($error, $description)
	{

		require_once ROOT_SITE . 'pages/message.php';

		die();

	}

	// Загружает индивидуальный шаблон.
	public static function show_pattern($attachment)
	{

		require_once ROOT_SITE.'pages/show_image.php';

		die();

	}

	// Выводит на экран ошибки запросов к бд, можно было бы расширить
	// вплоть до строки и файла с ошибкой, но это тестовое задание,
	// едва ли его будет кто то поддерживать после.
	public static function mysql_errors($message, $code, $error)
	{

		if(GLOBAL_ERRORS)
		{

			die($message . ', код: ' . $code . ', '. $error);

		}

		die();

	}

	// Нужно вывести текст ошибки и завершить выполнение скрипта? Тогда вы по адресу.
	public static function show_error($error)
	{

		if(GLOBAL_ERRORS)
		{

			die($error);

		}

		die();

	}

}