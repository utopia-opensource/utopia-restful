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
						$logic->printError('you must set collection_name parameter');
					}
					
					$level++;
					$sticker_name = $logic->solver->getPart($level);
					if($sticker_name == '') {
						$logic->printError('you must set sticker_name parameter');
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
						$logic->printError('you must set channelid parameter');
					}
					$result = $logic->getChannelInfo($channelid);
					$logic->printResult($result);
					break;
				case 'moderators':
					//getChannelModerators
					$level++;
					$channelid = $logic->solver->getPart($level);
					if($channelid == '') {
						$logic->printError('you must set channelid parameter');
					}
					$result = $logic->getChannelModerators($channelid);
					$logic->printResult($result, false);
					break;
				case 'contacts':
					//getChannelContacts
					$level++;
					$channelid = $logic->solver->getPart($level);
					if($channelid == '') {
						$logic->printError('you must set channelid parameter');
					}
					$result = $logic->getChannelContacts($channelid);
					$logic->printResult($result, false);
					break;
				case 'system_info':
					//getChannelSystemInfo
					$level++;
					$channelid = $logic->solver->getPart($level);
					if($channelid == '') {
						$logic->printError('you must set channelid parameter');
					}
					$result = $logic->getChannelSystemInfo($channelid);
					$logic->printResult($result);
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
	