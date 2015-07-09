<?php

	// Repeatedly extract text between two delimiters
	function ExtractText($text, $start_tag, $end_tag) {
		$start_tag_len = strlen($start_tag);
		$end_tag_len = strlen($end_tag);
		$extracted = array();
		$pos = 0;

		while (($pos = strpos($text, $start_tag, $pos))!==false) {
			if (($pos_end_tag = strpos($text, $end_tag, $pos+$start_tag_len))!==false) {
				$extracted[] = substr($text,$pos+$start_tag_len,$pos_end_tag-$pos-$start_tag_len);
				$pos = $pos_end_tag+$end_tag_len;
			} else {
				break;
			}
		}

		return $extracted;
	}