<?php
	namespace UtopiaREST;
	class Environment {
		function __construct() {
			$this->loadFromENV();
		}
		
		function loadFromENV() {
			$dotenv = \Dotenv\Dotenv::create(__DIR__ . "/../");
			$dotenv->load();
		}
	}
	