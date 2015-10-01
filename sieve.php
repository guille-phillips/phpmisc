<?php

	header('Content-Type: text/plain');

	$number = 2;
	$sqrt = sqrt($number);

	$array = array();

	// Initialise array
	for ($fill=0; $fill<=$number*2; $fill++) {
		$array[$fill] = false;
	}

	// Strike off composite numbers
	$step = 2;

	while ($step <= $sqrt) {
		echo "Step is ".$step."\n";

		for ($index = $step; $index<=$number*2; $index+=$step) {
			$array[$index] = true;
		}

		// Find next prime
		$look_index = $step+1;
		while ($array[$look_index]==true) {
			$look_index++;
		}

		$step = $look_index;

	}

	echo "\n";
	print_r($array);

?>