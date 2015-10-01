<?php
	header('Content-Type: text/plain');
	
	include 'parser_compiler.php';
	ini_set('html_errors', false);
	
	$rules = <<<RULES
		name list set case abcdefghijklmnopqrstuvwxyz | | |
		number list set 0123456789 | | |
		every and omit / number | |
		range and number omit - number opt every | | |
		member or range number | |
		group and omit ( list element del omit , | omit ) | |
		element and opt ~ | or group member | opt subarray | | |
		array and opt name | omit : list element del omit , | | |
		subarray and omit { array omit } | |
RULES;
	$x = new Parser();
	$x->CreateParser($rules);

	$file= new Stream("H:1{M:1,2,3},(5-20,23){M:4},~12-17/2");
	$result = $x->rules["array"]->Parse($file);

	if ($result->ok) {
		//echo $result->text($file);
		$set = ParseSet($result,$file);
		print_r($set);
	} else {
		die('not matched');
	}
	
	function ParseSet($tree,$file) {
		echo "\n";
		// echo $tree->nodes{0}->text($file);
		
		$class = $tree->nodes{0}->text($file);
		switch ($class) {
			case 'H':
				$array = array();
				for ($hour = 0; $hour <=23; $hour++) {
					$array{$hour} = false;
				}
				break;
			case 'M':
				$array = array();
				for ($minute = 0; $minute <=5; $minute++) {
					$array{$minute} = false;
				}
				break;
			case 'W':
				$array = array('MON'=>false,'TUE'=>false,'WED'=>false,'THU'=>false,'FRI'=>false,'SAT'=>false,'SUN'=>false);
		}
		
		foreach ($tree->nodes[1]->nodes as $node) {
			// echo $node->text($file);
			echo $node->index(0);
			$exclude = $node->index(0)==1;
			$value = $exclude?false:true;
			$element = $node->node(1);
			if ($node->index(2)==1) { // subarray
				$value = ParseSet($node->node(2,0,0),$file);
			}
			switch ($element->index) {
				case 1: // group
					$group = $element->node(0,0);
					echo ':'.$group->index;
					ParseGroup($array,$value,$group,$file);
					break;
				case 2: // member
					echo ':'.$element->index(0);
					switch ($element->index(0)) {
						case 1: // range
							$start = $element->node(0,0,0)->text($file);
							$end = $element->node(0,0,1)->text($file);
							$step = 1;
							if ($element->index(0,0,2)==1) {
								$step = $element->node(0,0,2)->text($file);
							}
							echo '>'.$start.'>'.$end;
							AddRange($array,$start,$end,$step, $value);
							break;
						case 2: // number
							$number = $element->node(0)->text($file);
							echo '>'.$number;
							AddRange($array,$number,$number, 1, $value);
							break;
					}
					break;
			}
			echo "\n";
		}
		
		return $array;
	}
	
	function ParseGroup(&$array,&$value,$tree,$file) {
		echo "\nG:";
		
		foreach ($tree->nodes as $node) {
			$exclude = $node->index(0)==1;
			$this_value = $exclude?false:$value;
			$element = $node->node(1);
			echo $element->index;
			if ($node->index(2)==1) { // subarray
				$value = ParseSet($node->node(2,0,0),$file);
			}
			
			switch ($element->index) {
				case 1: // group
					$group = $element->node(0,0);

					break;
				case 2: // member
					echo ':'.$element->index(0);
					switch ($element->index(0)) {
						case 1: // range
							$start = $element->node(0,0,0)->text($file);
							$end = $element->node(0,0,1)->text($file);
							$step = 1;
							if ($element->index(0,0,2)==1) {
								$step = $element->node(0,0,2)->text($file);
							}
							echo '>'.$start.'>'.$end;
							AddRange($array,$start,$end,$step, $this_value);
							break;
						case 2: // number
							$number = $element->node(0)->text($file);
							echo '>'.$number;
							AddRange($array,$number,$number, 1, $this_value);
							break;
					}
					break;
			}
			echo "\n";
		}
		
		return $array;
	}	
	exit;
	
	// $days = array('MON'=>false,'TUE'=>false,'WED'=>false,'THU'=>false,'FRI'=>false,'SAT'=>false,'SUN'=>false);
	// //AddMember($days,'WED');
	// AddRange($days,'THU','WED',3,true);
	
	// echo "\n";
	// print_r($days);
	
	function AddRange(&$set,$start,$end,$step,$value) {
		$finished = false;
		$start_pos = array_search($start,array_keys($set));
		if ($start_pos===false) throw new Exception('Member '.$start.' not found in set');
		$end_pos = array_search($end,array_keys($set));
		if ($end_pos===false) throw new Exception('Member '.$end.' not found in set');
		if ($step<1) throw new Exception('Step size must be more than or at least 1');
		$next_pos = 0;
		
		if ($start_pos<=$end_pos) {
			$pos = 0;
			foreach ($set as $key=>&$member) {
				if ($pos>=$start_pos && $pos<=$end_pos) {
					if ($pos==$next_pos) {
						$member = $value;
						$next_pos = $pos+$step;
					}
				} else {
					$next_pos = $pos+1;
				}
				$pos++;
			}
		} else {
			$pos = 0;
			foreach ($set as $key=>&$member) {
				if ($pos>=$start_pos) {
					if ($pos==$next_pos) {
						$member = $value;
						$next_pos = $pos+$step;
					}
				} else {
					$next_pos = $pos+1;
				}
				$pos++;
			}
			$next_pos -= $pos;
			$pos = 0;
			foreach ($set as $key=>&$member) {
				if ($pos<=$end_pos) {
					if ($pos==$next_pos) {
						$member = $value;
						$next_pos = $pos+$step;
					}
				} else {
					$next_pos = $pos+1;
				}
				$pos++;
			}			
		}
	}