<?php

	$travel = 30;
	$minimum_turnaround = 2;
	$interval = 5;

	$loop = $travel+2*$minimum_turnaround;

	$count = ceil($loop/$interval);

	$adjust = $count*$interval-$loop;

	$base_time = strtotime('08:00')+60;

	echo date('His', $base_time);