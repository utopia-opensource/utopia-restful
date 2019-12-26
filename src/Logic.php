<?php
	namespace UtopiaREST;
	
	class Logic {
		public $solver = null;   //UtopiaREST\Solver object
		public $last_error = ''; //string
		private $enviro = null;  //UtopiaREST\Environment object
		private $client = null;  //UtopiaLib\Client object
		
		public function __construct() {
			$this->enviro = new Environment();
			$this->client = new \UtopiaLib\Client(
				getenv('utopia_token'),
				'http://127.0.0.1',
				getenv('utopia_http_port')
			);
		}
		
		public function parseRequest(): bool {
			$request = Utilities::data_filter($_SERVER['REQUEST_URI']);
			$this->solver = new Solver();
			if(! $this->solver->parseRequest($request)) {
				$this->last_error = $this->solver->last_error;
				return false;
			} else {
				return true;
			}
		}
		
		function setJsonHeaders() {
			if(!headers_sent()) {
				header('Content-Type: application/json');
			}
		}
		
		function obj2json($data = []): string {
			return Utilities::json_readable_encode($data);
		}
		
		public function printError($info = '') {
			if($info == '') {
				$info = 'Information on this error is not available';
			}
			$this->setJsonHeaders();
			exit($this->obj2json(['error' => $info]));
		}
		
		public function printLastError() {
			$this->printError($this->last_error);
		}
		
		public function printResult($data = [], $humanReadable = true) {
			$this->setJsonHeaders();
			if($data == []) {
				exit('[]');
			}
			if($humanReadable) {
				exit($this->obj2json($data));
			} else {
				exit(json_encode($data));
			}
		}
		
		public function getStickerCollections(): array {
			return $this->client->getStickerCollections();
		}
		
		public function getStickerNamesByCollection($collection_name = ''): array {
			if($collection_name == '') {
				$collection_name = 'Default Stickers';
			}
			return $this->client->getStickerNamesByCollection($collection_name);
		}
		
		public function getImageSticker($collection_name = '', $sticker_name = 'airship'): string {
			return $this->client->getImageSticker($collection_name, $sticker_name, 'BASE64');
		}
		
		public function getChannels($search_filter = '', $channel_type = ''): array {
			if($channel_type == '') {
				$channel_type = '0';
			}
			$query_filter = new \UtopiaLib\Filter('', '', 50);
			return $this->client->getChannels($search_filter, $channel_type, $query_filter);
		}
		
		public function getChannelInfo($channelid = ''): array {
			return $this->client->getChannelInfo($channelid);
		}
		
		public function getChannelModerators($channelid = ''): array {
			return $this->client->getChannelModerators($channelid);
		}
		
		public function getChannelContacts($channelid = ''): array {
			return $this->client->getChannelContacts($channelid);
		}
		
		public function getChannelSystemInfo($channelid = ''): array {
			return $this->client->getChannelSystemInfo($channelid);
		}
		
		public function unsSearchByNick($pkOrNick = ''): array {
			return $this->client->unsSearchByNick($pkOrNick);
		}
		
		public function unsSearchByPk($pkOrNick = ''): array {
			return $this->client->unsSearchByPk($pkOrNick);
		}
		
		public function getUnsSyncInfo(): array {
			return $this->client->getUnsSyncInfo();
		}
		
		public function getWhoIsInfo($pkOrNick = ''): array {
			return $this->client->getWhoIsInfo($pkOrNick);
		}
		
		public function ucodeEncode($hex = ''): string {
			return $this->client->ucodeEncode($hex, 256, 'BASE64', 'JPG');
		}
		
		public function ucodeDecode($base64_image = ''): string {
			return $this->client->ucodeDecode($base64_image);
		}
		
		public function sendChannelMessage($channelid, $message = "test message"): string {
			return $this->client->sendChannelMessage($channelid, $message);
		}
	}
	