<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	
	$logic = new UtopiaREST\Logic();
	if(! $logic->parseRequest()) {
		$logic->printLastError();
	}
	
	$level = 2;
	switch($logic->solver->getRoot()) {
		default:
			$logic->printError('Invalid request');
		case 'help':
			UtopiaREST\HelpProvider::printHelp();
		case 'sticker':
			$level++;
			switch($logic->solver->getPart($level)) {
				default:
					$logic->printError('Invalid request');
				case 'collections':
					//getStickerCollections
					$result = $logic->getStickerCollections();
					$logic->printResult($result, false);
					break;
				case 'names':
					//getStickerNamesByCollection
					$level++;
					$collection_name = $logic->solver->getPart($level);
					$result = $logic->getStickerNamesByCollection($collection_name);
					$logic->printResult($result);
					break;
				case 'image':
					//getImageSticker
					$level++;
					$collection_name = $logic->solver->getPart($level);
					if($collection_name == '') {
						$logic->printError('you must set <collection_name> parameter');
					}
					
					$level++;
					$sticker_name = $logic->solver->getPart($level);
					if($sticker_name == '') {
						$logic->printError('you must set <sticker_name> parameter');
					}
					
					$result = $logic->getImageSticker($collection_name, $sticker_name);
					$logic->printResult(['result' => $result]);
					break;
			}
			break;
		case 'channel':
			$level++;
			switch($logic->solver->getPart($level)) {
				default:
					$logic->printError('Invalid request');
				case 'list':
					//getChannels
					$level++;
					$filter = $logic->solver->getPart($level);
					$level++;
					$channel_type = $logic->solver->getPart($level);
					$result = $logic->getChannels($filter, $channel_type);
					$logic->printResult($result, false);
					break;
				case 'info':
					//getChannelInfo
					$level++;
					$channelid = $logic->solver->getPart($level);
					if($channelid == '') {
						$logic->printError('you must set <channelid> parameter');
					}
					$result = $logic->getChannelInfo($channelid);
					$logic->printResult($result);
					break;
				case 'moderators':
					//getChannelModerators
					$level++;
					$channelid = $logic->solver->getPart($level);
					if($channelid == '') {
						$logic->printError('you must set <channelid> parameter');
					}
					$result = $logic->getChannelModerators($channelid);
					$logic->printResult($result, false);
					break;
				case 'contacts':
					//getChannelContacts
					$level++;
					$channelid = $logic->solver->getPart($level);
					if($channelid == '') {
						$logic->printError('you must set <channelid> parameter');
					}
					$result = $logic->getChannelContacts($channelid);
					$logic->printResult($result, false);
					break;
				case 'system_info':
					//getChannelSystemInfo
					$level++;
					$channelid = $logic->solver->getPart($level);
					//TODO: use client->verifyChannelID method
					if($channelid == '') {
						$logic->printError('you must set <channelid> parameter');
					}
					$result = $logic->getChannelSystemInfo($channelid);
					$logic->printResult($result);
					break;
				case 'send_message':
					//sendChannelMessage
					$level++;
					$request_channelid = $logic->solver->getPart($level);
					if($request_channelid == '') {
						//TODO: these checks can be collected in a separate method
						$logic->printError('you must set <channelid> parameter');
					}
					$level++;
					$request_message = $logic->solver->getPart($level);
					if($request_message == '') {
						$logic->printError('you must set <message> parameter');
					}
					$level++;
					$request_token   = $logic->solver->getPart($level);
					if($request_token == '') {
						$logic->printError('you must set <token> parameter');
					}
					if(strlen($request_token) != 32) {
						//TODO: create verifyToken method in utopia-php lib
						$logic->printError('invalid token given. Code 94VD0E');
					}
					if(! $logic->checkClientToken($request_token)) {
						$logic->printError('invalid token given. Code 0Z2L1F');
					}
					$result = $logic->sendChannelMessage($request_channelid, $request_message);
					$logic->printResult(['result' => $result]);
					break;
				case 'send_image':
					$level++;
					$request_channelid = $logic->solver->getPart($level);
					if($request_channelid == '') {
						//TODO: these checks can be collected in a separate method
						$logic->printError('you must set <channelid> parameter');
					}
					$level++;
					$img_url = base64_decode($logic->solver->getPart($level));
					if($img_url == '') {
						$logic->printError('you must set <image_url> parameter');
					}
					//TODO: verify url
					$level++;
					$request_token = $logic->solver->getPart($level);
					if($request_token == '') {
						$logic->printError('you must set <token> parameter');
					}
					if(strlen($request_token) != 32) {
						//TODO: create verifyToken method in utopia-php lib
						$logic->printError('invalid token given. Code 94VD0F');
					}
					$level++;
					//$comment = $logic->solver->getPart($level);
					
					if(! $logic->checkClientToken($request_token)) {
						$logic->printError('invalid token given. Code 0Z2L1G');
					}
					
					$image_file = file_get_contents ($img_url, false);
					if($image_file == false) {
						$logic->printError('Failed to upload image. Code 4LX1Z8');
					}
					$image_base64 = base64_encode($image_file);
					
					$result = $logic->sendChannelPicture($request_channelid, $image_base64);
					$logic->printResult(['result' => $result]);
					break;
			}
			break;
		case 'uns':
			$level++;
			switch($logic->solver->getPart($level)) {
				default:
					$logic->printError('Invalid request');
					break;
				case 'search':
					$level++;
					$pkOrNick = $logic->solver->getPart($level);
					if($pkOrNick == '') {
						$logic->printError('you must set publickey or nick parameter');
					}
					switch(count($pkOrNick)) {
						default:
							//nick -> unsSearchByNick
							$result = $logic->unsSearchByNick($pkOrNick);
							break;
						case 64:
							//public key -> unsSearchByPk
							$result = $logic->unsSearchByPk($pkOrNick);
							break;
					}
					$logic->printResult($result);
					break;
				case 'sync_info':
					//getUnsSyncInfo
					$level++;
					$result = $logic->getUnsSyncInfo();
					$logic->printResult($result);
					break;
				case 'whois':
					//getWhoIsInfo
					$level++;
					$pkOrNick = $logic->solver->getPart($level);
					if($pkOrNick == '') {
						$logic->printError('you must set publickey or nick parameter');
					}
					$result = $logic->getWhoIsInfo($pkOrNick);
					$logic->printResult($result);
					break;
			}
			break;
		case 'ucode':
			$level++;
			switch($request_parts[$level]) {
				default:
					$logic->printError('Invalid request');
					break;
				case 'encode':
					//ucodeEncode
					$level++;
					$hex = $logic->solver->getPart($level);
					if($hex == '') {
						$logic->printError('you must set hex parameter');
					}
					$result = $logic->ucodeEncode($hex);
					$logic->printResult(['result' => $result]);
					break;
				case 'decode':
					//ucodeDecode
					$level++;
					$base64_image = $logic->solver->getPart($level);
					if($base64_image == '') {
						$logic->printError('you must set image parameter');
					}
					$result = $logic->ucodeEncode($hex);
					$logic->printResult(['result' => $result]);
					break;
			}
			break;
		case 'rates':
			$level++;
			$to_currency = $logic->solver->getPart($level);
			$rates_provider = new UtopiaREST\RatesProvider();
			$rates = $rates_provider->getRates($to_currency);
			if($rates == []) {
				$logic->printError('Failed to get course data. ' . $rates_provider->last_error);
			} else {
				$logic->printResult($rates);
			}
			break;
	}
	