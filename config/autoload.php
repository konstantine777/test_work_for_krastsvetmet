<?php

/**
 * Функция автозагрузки, расчитана только на загрузку классов из
 * пространства имен.
 */
spl_autoload_register(function ($file)
{
	include ROOT_SITE.str_replace('\\', '/', $file).".php";
});