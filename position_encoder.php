<?php

	require_once 'conversion.php';

	class PositionEncoder {

		static public function GetEncoding($list) {
			$pattern = '0';
			$multiplier = 1;
			foreach ($list as $member) {
				$pattern = Conversion::BinaryMultiply($pattern,Conversion::Binary($multiplier));
//echo $pattern.'<br>';
				$pattern = Conversion::BinaryAdd($pattern,Conversion::Binary($member[1]));
				$multiplier = $member[0];
			}

			return $pattern;
		}

	}

	echo PositionEncoder::GetEncoding(array(array(16,12),array(16,13),array(16,15)));