<?php
	namespace UtopiaREST;
	
	class RatesProvider {
		public $last_error = '';
		
		function getRatesPath(): string {
			return __DIR__ . '/../data/crypton_rates.json';
		}
		
		public function getRates($to_currency = ''): array {
			if(!file_exists($this->getRatesPath())) {
				$this->last_error = 'Could not find rates data container. Code 9V4MS1';
				return [];
			}
			//$this->last_error = 'test error';
			$rates_json = file_get_contents($this->getRatesPath());
			if(! Utilities::isJson($rates_json)) {
				return [];
			}
			return json_decode($rates_json, true);
		}
		
		function CoinMarketCapGET($endpoint = 'cryptocurrency/map', $parameters = []): string {
			$url = 'https://pro-api.coinmarketcap.com/v1/' . $endpoint;

			$headers = [
			  'Accepts: application/json',
			  'X-CMC_PRO_API_KEY: ' . getenv('cmc_key')
			];
			$qs = http_build_query($parameters); // query string encode the parameters
			$request = $url . '?' . $qs; // create the request URL

			$curl = curl_init(); // Get cURL resource
			// Set cURL options
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $request,            // set the request URL
			  CURLOPT_HTTPHEADER => $headers,     // set the headers 
			  CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
			));

			$response = curl_exec($curl); // Send the request, save the response
			//print_r(json_decode($response)); // print json decoded response
			curl_close($curl); // Close request
			return $response;
		}
		
		function CoinLibGET($endpoint = 'coin', $params = []): string {
			$api_params = http_build_query($params);
			$api_url    = 'https://coinlib.io/api/v1/' . $endpoint . '?key=' . getenv('coinlib_key') . '&' . $api_params;
			return Utilities::curlGET($api_url);
		}
		
		public function updateRates() {
			$api_endpoint = 'coin';
			$api_parameters = [
				'pref'   => 'USD',
				'symbol' => 'BTC,ETH,XMR,MFC,LTC,DOGE'
			];
			//deprecated
			//echo $this->CoinMarketCapGET($api_endpoint, $api_parameters);
			$result = $this->CoinLibGET($api_endpoint, $api_parameters);
			//file_put_contents(__DIR__ . '/../cron/result.txt', $result); //debug
			if(! Utilities::isJson($result)) {
				return;
			}
			
			$rates_data = [];
			$round_precisions = [
				'USD' => 4,
				'RUB' => 2,
				'EUR' => 4,
				'BTC' => 8,
				'LTC' => 6,
				'ETH' => 6
			];
			
			$parsed_data = json_decode($result, true);
			for($i=0; $i < count($parsed_data['coins']); $i++) {
				$coin_info = $parsed_data['coins'][$i];
				$coin_tag  = $coin_info['symbol'];
				$price = $coin_info['price'] + 0;
				if($price == 0) {
					$price = 0;
				} else {
					if(isset($round_precisions[$coin_tag])) {
						$precision = $round_precisions[$coin_tag];
					} else {
						$precision = 4;
					}
					$rates_data[$coin_tag] = number_format(1 / $price, $precision, '.', '');
				}
			}
			
			file_put_contents($this->getRatesPath(), json_encode($rates_data));
		}
	}
	