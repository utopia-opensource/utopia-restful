<?php
	namespace UtopiaREST;
	
	class HelpProvider {
		public static function getHelp(): array {
			return [
				'version'  => '1.0.0',
				'date'     => '2019-09-11',
				'commands' => [
					'/api/sticker/collections' => 'returns collection names of stickers',
					'/api/sticker/names/<collection_name>' => 'returns available names from corresponded collection',
					'/api/sticker/image/<collection_name>/<sticker_name>' => 'returns image (base64 encoded) by sticker name from corresponded collection',
					'/api/channel/list' => 'returns the current list of all channels of Utopia ecosystem',
					'/api/channel/list/<filter>' => 'search by name of the channel (partial or complete matching)',
					'/api/channel/list/<filter>/<channel_type>' => 'search by channel_type: 0-All, 1-Recent, 2-My, 3-Friends, 4-Bookmarked, 5-Joined',
					'/api/channel/info/<channelid>' => 'returns the information about the channel',
					'/api/channel/moderators/<channelid>' => 'returns the list of public keys of moderators',
					'/api/channel/contacts/<channelid>' => 'returns the list of contacts on channel with details',
					'/api/channel/system_info/<channelid>' => 'returns system properties of channel',
					'/api/uns/search/<pubkey>' => 'returns the list of all uNS names registered for this public key',
					'/api/uns/search/<nick>' => 'returns the list of all uNS names registered for this nick',
					'/api/uns/sync_info' => 'returns statistics value of sync process',
					'/api/uns/whois/<pubkey>' => 'returns the detailed information about selected user by pubkey',
					'/api/uns/whois/<nick>' => 'returns the detailed information about selected user by nick',
					'/api/ucode/encode/<hex>' => 'returns image (base64 encoded) of ucode generated by hex string',
					'/api/ucode/decode/<image>' => 'returns hex public key from image in base64 format'
				]
			];
		}
		
		public static function printHelp() {
			if(!headers_sent()) {
				header('Content-Type: application/json');
			}
			echo Utilities::json_readable_encode(HelpProvider::getHelp()); exit;
		}
	}
	