<?php	
	require_once 'conversion.php';

	class Base64 {
		static private $symbols = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/';

		static public function Encode($binary_digits) {
			$length = strlen($binary_digits);
			$symbol_stream = substr(self::$symbols,$length%6,1);
			$pad = 6-($length%6);
			if ($pad<6) {
				$binary_digits = str_pad($binary_digits, $pad, '0');
				$length+=$pad;
			}

			for ($hexad=0; $hexad < $length; $hexad+=6) {
				$value = 0;
				$multiplier=1;
				for ($digit_pos = 0; $digit_pos<6; $digit_pos++) {
					$value = $value+$multiplier*intval(substr($binary_digits,$hexad+$digit_pos,1));
					$multiplier*=2;
				}
				$symbol_stream .= substr(self::$symbols,$value,1);
			}
			return $symbol_stream;
		}

		static public function Decode($base64) {
			$binary_digits = '';

			$alignment = strpos(self::$symbols,substr($base64,0,1));

			if ($alignment===false) {
				return false;
			}
			if ($alignment>0) {
				$alignment = 6-$alignment;
			}

			$length = strlen($base64);
			for ($symbol_pos = 1; $symbol_pos < $length; $symbol_pos++) {
				$value = strpos(self::$symbols,substr($base64,$symbol_pos,1));
				if ($value===false) {
					return false;
				} else {
					$binary_digits .= Conversion::Binary($value,6);
				}
			}

			return substr($binary_digits,0,strlen($binary_digits)-$alignment);
		}

	}