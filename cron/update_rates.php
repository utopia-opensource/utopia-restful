<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	
	$logic = new UtopiaREST\Logic();
	$rates_provider = new UtopiaREST\RatesProvider();
	$rates_provider->updateRates();
	