<?php
	$logs = array(2=>103,3=>163,5=>239,7=>289,11=>356,13=>381,17=>421,19=>437.5);
	
	for ($number = 1; $number<=256; $number++) {
		echo $number;
		echo ' ';
		//var_dump(factorise($number));
		echo logarithm(factorise($number));
		echo '<br>';
	}

	function factorise($number) {

		$factors = array();

		$root = floor(sqrt($number));

		for ($divisor = 1; $divisor<=$root; $divisor+=2) {
			$factor = $divisor==1?2:$divisor;
			if ($number%$factor==0) {
				$number = $number/$factor;
				$factors[] = $factor;
				$divisor = -1;
			}
		}
		if ($number>1) {
			$factors[] = $number;
		}

		return $factors;

	}


	function logarithm($factors) {
		global $logs;
		$sum = 0;
		foreach ($factors as $factor) {
			if (isset($logs[$factor])) {
				$sum += $logs[$factor];
			} else {
				return 0;
			}
		}
		return $sum;
	}

?>