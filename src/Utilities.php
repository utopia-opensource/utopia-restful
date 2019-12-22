<?php
	namespace UtopiaREST;
	
	class Utilities {
		function isJson($string = ""): bool {
			return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
		}
		
		function data_filter($string = "", $db_link = null) {
			//\UtopiaREST\Utilities::data_filter
			$string = strip_tags($string);
			$string = stripslashes($string);
			$string = htmlspecialchars($string);
			$string = trim($string);
			if(isset($db_link) && $db_link != null) {
				$string = $db_link->filter_string($string);
			}
			return $string;
		}
		
		function cURL($url, $ref, $header, $cookie, $p = null): string {
			$ch =  curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			if(isset($_SERVER['HTTP_USER_AGENT'])) {
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			}
			if($ref != '') {
				curl_setopt($ch, CURLOPT_REFERER, $ref);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			if($cookie != '') {
				curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			}
			if ($p) {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
			}
			$result =  curl_exec($ch);
			curl_close($ch);
			if ($result){
				return $result;
			} else {
				return '';
			}
		}
		
		function curlGET($url): string {
			return Utilities::cURL($url, '', '', '');
		}
		
		function json_decode_nice($json = "", $assoc = FALSE){ 
			$json = str_replace(["\n", "\r"], "", $json);
			$json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$json);
			$json = preg_replace('/(,)\s*}$/', '}', $json);
			return json_decode($json, $assoc);
		}
		
		function json_escape($json) {
			//\UtopiaREST\Utilities::json_escape
			return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $json);
		}
		
		function json_readable_encode($in, $indent = 0, $from_array = false)
		{
			//\UtopiaREST\Utilities::json_readable_encode
			//$_myself = __FUNCTION__;

			$out = '';

			foreach ($in as $key => $value)
			{
				$out .= str_repeat("\t", $indent + 1);
				$key_escaped = \UtopiaREST\Utilities::json_escape((string) $key);
				$escape_symb = '"';
				//if(is_int($key)) {
				//	$escape_symb = '';
				//}
				$out .= $escape_symb . $key_escaped . $escape_symb . ': ';

				if (is_object($value) || is_array($value))
				{
					$out .= "\n";
					$out .= \UtopiaREST\Utilities::json_readable_encode($value, $indent + 1);
				}
					elseif (is_bool($value))
				{
					$out .= $value ? 'true' : 'false';
				}
					elseif (is_null($value))
				{
					$out .= 'null';
				}
					elseif (is_string($value))
				{
					$out .= "\"" . \UtopiaREST\Utilities::json_escape($value) ."\"";
				}
					else
				{
					$out .= $value;
				}

				$out .= ",\n";
			}

			if (!empty($out))
			{
				$out = substr($out, 0, -2);
			}

			$out  = str_repeat("\t", $indent) . "{\n" . $out;
			$out .= "\n" . str_repeat("\t", $indent) . "}";

			return $out;
		}
	}
	