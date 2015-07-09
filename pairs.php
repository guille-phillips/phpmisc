<?php

ini_set('max_execution_time', 0); //300 seconds = 5 minutes
//ini_set('memory_limit','16M');

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

 $myfile = fopen("romeo.txt", "r");
 $string = fread($myfile,filesize("romeo.txt"));
 fclose($myfile);


/*	$string='Epistemology is concerned with the nature and scope of knowledge,[11] such as the relationships between truth, belief, perception and theories of justification.

Skepticism is the position which questions the possibility of completely justifying any truth. The regress argument, a fundamental problem in epistemology, occurs when, in order to completely prove any statement, its justification itself needs to be supported by another justification. This chain can do three possible options, all of which are unsatisfactory according to the MŸnchhausen trilemma. One option is infinitism, where this chain of justification can go on forever. Another option is foundationalism, where the chain of justifications eventually relies on basic beliefs or axioms that are left unproven. The last option, such as in coherentism, is making the chain circular so that a statement is included in its own chain of justification.';
	*/
	//$string='abaacaaka';
	
	//echo $string.'<br><br>';
	
	$x = new Compress($string);
	echo $x->original_size.':'.$x->compressed_size.'='.($x->compressed_size/$x->original_size).'<br><br>';
	
	//var_dump($x->compressed);
	$y = new Decompress($x->compressed);
	echo $y->decompressed;

	exit;
	
	class Compress {
		var $compressed;
		var $original_size;
		var $compressed_size;
		
		function __construct($text) {
			$dictionary = array();
			$symbols = array();

			$spacing = 1;

			$numbers = $this->ConvertToNumbers($text,$symbols);
			$this->original_size = count($numbers);
			$pairs = $this->CountPairs($numbers,$spacing);

			$code = $this->NextCode($symbols);

		 	do {
			 	$highest_pair = $this->HighestPair($pairs);
				
			 	if ($highest_pair!==false) {
			 		$dictionary[] = array($code,$highest_pair[0],$highest_pair[1]);
			 		$this->Replace($numbers,$pairs,$symbols,$code,$pairs[$highest_pair[0]][$highest_pair[1]][1],$spacing);
			 	}

			 	$code = $this->NextCode($symbols);
			} while ($highest_pair!==false);

		 	$this->compressed = $this->Compressed($dictionary,$numbers);
		 	$this->compressed_size = count($this->compressed);		
		}

		function NextCode(&$symbols) {
			$current_code = 0;
			while (isset($symbols[$current_code])) {
				$current_code++;
			}
			return $current_code;
		}

	 	private function ConvertToNumbers($string,&$symbols) {
	 		$numbers = array();
	 		$length = strlen($string);
			for ($index = 0; $index<$length; $index++) {
				$char = substr($string,$index,1);
				if (strtolower($char)!=$char) {
					$numbers[] = 0;
					$this->AddSymbol($symbols,0);
					$numbers[] = ord(strtolower($char));
					$this->AddSymbol($symbols,ord(strtolower($char)));
				} else {
					$numbers[] = ord($char);
					$this->AddSymbol($symbols,ord($char));
				}
			}
			return $numbers;
	 	}

		private function CountPairs($numbers,$spacing) {
			$pairs = array();
			$last_pair = count($numbers)-$spacing-1;
			$code1prev = -1;
			for ($index = 0; $index<=$last_pair; $index++) {
				$code1 = $numbers[$index];
				$code2 = $numbers[$index+$spacing];

				if ($code2==$code1 && $code1==$code1prev) {
				} else {
					if (!isset($pairs[$code1][$code2])) {
						$pairs[$code1][$code2] = array(1,array($index=>$index));
					} else {
						$pairs[$code1][$code2][0]++;
						$pairs[$code1][$code2][1][$index] = $index;
					}					
				}

				$code1prev = $code2;
			}
			return $pairs;
		}
		
		private function HighestPair($pairs) {
			$highest_count = 0;
			foreach ($pairs as $first_code => $first_codes) {
				foreach ($first_codes as $second_code => $info) {
					if ($info[0]>$highest_count) {
						$code1 = $first_code;
						$code2 = $second_code;
						$highest_count = $info[0];
					}
				}
			}
			if ($highest_count>=3) {
				return array($code1,$code2);
			} else {
				return false;
			}
		}

		private function Replace(&$numbers,&$pairs,&$symbols,$code,$positions,$spacing) {
			$index = 0;
			foreach ($positions as $position) {
				$next_pos = $this->FindNext($numbers,$position,$spacing);
				$next_pos2 = $this->FindNext($numbers,$position,$spacing*2);
				$prev_pos = $this->FindNext($numbers,$position,-$spacing);
				
				$this->RemovePair($pairs,$numbers[$position],$numbers[$next_pos],$position);
				if ($prev_pos!==false) {
					$this->RemovePair($pairs,$numbers[$prev_pos],$numbers[$position],$prev_pos);
				}
				if ($next_pos!==false) {
					$this->RemovePair($pairs,$numbers[$next_pos],$numbers[$next_pos2],$next_pos);
				}
				
				$this->RemoveSymbol($symbols,$numbers[$position]);
				$this->RemoveSymbol($symbols,$numbers[$next_pos]);
				$this->AddSymbol($symbols,$code);

				$numbers[$position] = $code;
				$numbers[$next_pos] = -1;

				$next_pos = $this->FindNext($numbers,$position,$spacing);
				
				if ($prev_pos!==false) {
					$this->AddPair($pairs,$numbers[$prev_pos],$code,$prev_pos);
				}
				if ($next_pos!==false) {
					$this->AddPair($pairs,$code,$numbers[$next_pos],$position);
				}
				
				$index++;
				if (($index%100)==0) {
					$a=1;
				}
			}
		}

		private function FindNext($numbers, $position, $offset) {
			$max = count($numbers)-1;
			if ($offset<0) {
				$direction = -1;
			} elseif ($offset>0) {
				$direction = 1;
			} else {
				$direction = 0;
			}
			
			$position+=$direction;
			while ($position<=$max && $position >=0 && $offset!=0) {
				if ($numbers[$position]!=-1) {
					$offset-=$direction;
				}
				if ($offset!=0) {
					$position+=$direction;
				}
			}

			if ($offset==0) {
				return $position;
			} else {
				return false;	
			}
		}

		private function RemoveSymbol(&$symbol,$ord) {
			if (isset($symbol[$ord])) {
				$symbol[$ord]--;
				if ($symbol[$ord]==0) {
					unset($symbol[$ord]);
				}
			}
		}

		private function AddSymbol(&$symbol, $ord) {
			if (isset($symbol[$ord])) {
				$symbol[$ord]++;
			} else {
				$symbol[$ord] = 1;
			}	
		}

		private function RemovePair(&$pairs,$code1,$code2,$position) {
			if (isset($pairs[$code1]) && isset($pairs[$code1][$code2]) && isset($pairs[$code1][$code2][1][$position])) {
				$pairs[$code1][$code2][0]--;
				unset($pairs[$code1][$code2][1][$position]);
				if ($pairs[$code1][$code2][0]==0) {
					unset($pairs[$code1][$code2]);
					if (count($pairs[$code1])==0) {
						unset($pairs[$code1]);
					}
				}
			}
		}

		private function AddPair(&$pairs,$code1,$code2,$position) {
			if (isset($pairs[$code1]) && isset($pairs[$code1][$code2])) {
				if (!isset($pairs[$code1][$code2][1][$position])) {
					$pairs[$code1][$code2][0]++;
					$pairs[$code1][$code2][1][$position] = $position;
				}
			} else {
				$pairs[$code1][$code2] = array(1,array($position=>$position));
			}
		}
		
		private function Compressed(&$dictionary,&$numbers) {
			$output = array();
			$dictionary = array_reverse($dictionary);
			$output[] = count($dictionary);
			foreach ($dictionary as $replacement) {
				$output[]=$replacement[0];
				$output[]=$replacement[1];
				$output[]=$replacement[2];
			}
			foreach ($numbers as $number) {
				if ($number!=-1) {
					$output[]=$number;
				}
			}
			return $output;
		}
	}

	class Decompress {
		var $decompressed;

		function __construct($data) {
			$dictionary_info = $this->ReadDictionary($data);
			$dictionary = $dictionary_info[0];
			$numbers = array_slice($data,$dictionary_info[1]);

			foreach ($dictionary as $replacement) {
				do {
					$position = array_search($replacement[0],$numbers);
					if ($position!==false) {
						$numbers[$position] = $replacement[1];
						array_splice($numbers,$position+1,0,array($replacement[2]));
					}
				} while ($position!==false);
			}
			$this->decompressed = $this->ConvertToText($numbers);
		}

		private function ConvertToText(&$numbers) {
			$text = '';
			$upper = false;
			foreach ($numbers as $ord) {
				if ($ord==0) {
					$upper=true;
				} else {
					$text .= $upper?strtoupper(chr($ord)):chr($ord);
					$upper = false;
				}
			}
			return $text;
		}

		private function ReadDictionary(&$data) {
			$dictionary = array();
			$count = $data[0];
			for ($index = 0; $index<$count; $index++) {
				$dictionary[] = array($data[1+$index*3],$data[2+$index*3],$data[3+$index*3]);
			}
			return array($dictionary,$count*3+1);
		}
	}
?>