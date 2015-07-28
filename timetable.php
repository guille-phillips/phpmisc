<?php
/*
	$travel = 30;
	$minimum_turnaround = 2;
	$interval = 5;

	$loop = $travel+2*$minimum_turnaround;

	$count = ceil($loop/$interval);

	$adjust = $count*$interval-$loop;

	$base_time = strtotime('08:00')+60;

	echo date('Hi s', $base_time);
*/

	//Pattern('((10,11,12),9,8,(7,8,9),6,5,4,3,2,1)');

	Pattern('(5,4,3,2,1)xxx');

	function Pattern($interval_pattern) {
		$match = 
		<<<REGEX
			/(?xJ)
			(?(DEFINE)
				(?<number> (\d+) )
				(?<member> (?&number)|(?&list) )
				(?<list> \( ((?&number)|(?&member))(,(?&member))* \) ) 
			)
			^(?&list)/
REGEX;

		//$match = '/(\d)((,\d)*)/';
		$matches = array();
		if (preg_replace_callback($match,"test",$interval_pattern)==1) {
			print_r($matches);
		}
	}

	function test($value) {
		print_r($value);
		echo '<br>';
	}

/*
					(?<range> (?&number)-(?&number)(\/(?&number))? )
				(?<numorrange> (?&range)|(?&number) )
				(?<list2> (?&numorrange)(,(?&numorrange))* )
				*/