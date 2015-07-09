<?php

	//echo Conversion::Decimal(Conversion::BinaryMultiply('1001','1001'));

	class Conversion {
		static public function Binary($value, $places=false){
			if ($places===false) {
				$places = ceil(log($value+1)/log(2));
			}
			$digits = '';
			for ($index = 0; $index < $places; $index++) {
				$digits .= $value%2;
				$value = floor($value/2);
			}
			return $digits;
		}

		static public function Decimal($value){
			$sum = 0;
			$multiplier = 1;
			$places = strlen($value);
			for ($index = 0; $index < $places; $index++) {
				$sum += intval(substr($value,$index,1))*$multiplier;
				$multiplier*=2;
			}
			return $sum;
		}

		static public function BinaryAdd($addend, $adder, $offset = 0) {
			$binary = '';
			$addend_len = strlen($addend);
			$adder_len = strlen($adder);
			$total = max($addend_len-$offset,$adder_len);

			$binary = substr(str_pad($addend,$offset,'0'),0,$offset);

			$carry = 0;
			for ($place = 0; $place<$total; $place++) {
				$bit1 = (($place+$offset)<$addend_len)?substr($addend,$place+$offset,1):'0';
				$bit2 = ($place<$adder_len)?substr($adder,$place,1):'0';
				$sum = intval($bit1)+intval($bit2)+$carry;
				$binary .= $sum%2;
				$carry = floor($sum/2);
			}
			if ($carry==1) {
				$binary .= '1';
			}
			return $binary;
		}

		static public function BinaryMultiply($multiplicand, $multiplier) {
			$result = '';
			$running = '0';

			$total = max(strlen($multiplicand),strlen($multiplier));

			$sum_length = self::BinaryLength($total);

			for ($place = 0; $place < ($total*2-1); $place++) {
				$sum = 0;
				for ($place1 = 0; $place1 <= $place; $place1++) {
					$place2 = $place - $place1;
					$sum += intval(substr($multiplicand,$place1,1))*intval(substr($multiplier,$place2,1));
				}
				//echo $sum.' '.$place.' '.self::Binary($sum,$sum_length).'<br>';
				
				$running = self::BinaryAdd($running, self::Binary($sum,$sum_length),$place);
			}

			return $running;
		}

		static public function BinaryLength($decimal) {
			return ceil(log($decimal+1)/log(2));
		}

	}