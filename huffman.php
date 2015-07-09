<?php

	class Huffman {
		static public function GetCodes($frequencies) {

			while (count($frequencies)>1) {
				usort($frequencies, function($a,$b){return ($a[1]<$b[1])?-1:($a[1]==$b[1]?($a[0]<$b[0]?-1:1):1);});

				$new = array($frequencies[0][0].$frequencies[1][0],$frequencies[0][1]+$frequencies[1][1],array($frequencies[0],$frequencies[1]));

				unset($frequencies[0]);
				unset($frequencies[1]);
				$frequencies[] = $new;
			}
			sort($frequencies);

			$codes = array();

			$depth_first = function($node,$path,&$codes) use (&$depth_first) {
				$digit = 0;
				if (isset($node[2])) {
					foreach ($node[2] as $sub_node) {
						$depth_first($sub_node,$path.$digit,$codes);
						$digit++;
					}
				} else {
					$codes[$node[0]] = $path;
				}
			};

			$depth_first($frequencies[0],'',$codes);

			return $codes;
		}
	}