<?php

namespace prototypes;


class Controller
{

	protected $model;
	protected $view;

	function __construct($model, $view)
	{

		$this->model = $model;
		$this->view = $view;

	}

}