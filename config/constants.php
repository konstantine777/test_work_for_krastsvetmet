<?php

// Символы из которых происходит генирация ключей, при желании, легко дополнить или сократить.
const CONFIGURATION = [
	'symbols_string' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
];

// Корень документа в более удобном формате.
define('ROOT_SITE', $_SERVER['DOCUMENT_ROOT'].'/');

// Эта константа отвечает за вывод сомосозданных ошибок, перед загрузкой на сервер,
// нужно поставить значение false.
define('GLOBAL_ERRORS', true);