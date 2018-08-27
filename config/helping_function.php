<?php

// Функция для генирации случайных ключей. Принимает количество блоков и количество символов в нем.
function createRandomKey($number_block_symbols = 1, $number_symbols_in_block = 12)
{

	$length_symbols_string = strlen(CONFIGURATION['symbols_string']) - 1;
	$answer = '';

	for ($i = 0; $i < $number_block_symbols; $i++)
	{

		for ($n = 0; $n < $number_symbols_in_block; $n++)
		{

			$rand_symbol = rand(0, $length_symbols_string);
			$string = CONFIGURATION['symbols_string'];
			$get_rand_symbol = $string{$rand_symbol};

			$answer .= $get_rand_symbol;

		}

		if($i < $number_block_symbols - 1)
		{

			$answer .= '-';

		}

	}

	return $answer;

}

// Удаляет слеши в конце строки, обычный while, пока последний символ является /.
function getNormalizeAddressString($addressString)
{

	while ($addressString[strlen($addressString) - 1] === '/')
	{

		$addressString = substr($addressString, 0, -1);

	}


	return $addressString;

}

// Для вывода интересующих данных.
function debug($obj)
{

	echo '<pre>';
	print_r($obj);
	echo '</pre>';

}

// Для проверки объема принятых значений. Не забыть бы вернуться.
function normalize_POST($POST)
{

	return $POST;

}

// Убрать уровень вложенности, для элементов массивов, представляющих массив с одним значением.
function remove_nesting($array)
{

	$normalize_array = [];

	foreach ($array as $value)
	{

		$normalize_array[] = $value[0];

	}

	return $normalize_array;

}