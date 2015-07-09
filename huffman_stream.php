<?php
	require_once 'huffman.php';

	require_once 'frequency.php';

	require_once 'base64.php';

// A:9
// B:2
// C:2
// D:5
// E:13
// F:2
// G:3
// H:4
// I:8
// J:1
// K:1
// L:4
// M:2
// N:5
// O:8
// P:2
// Q:1
// R:6
// S:5
// T:7
// U:4
// V:2
// W:2
// X:1
// Y:2
// Z:1
// BLANKS:2

	$f = new Frequencies(array());

	$f->Add('A',9);
	$f->Add('B',2);
	$f->Add('C',2);
	$f->Add('D',5);
	$f->Add('E',13);
	$f->Add('F',2);
	$f->Add('G',3);
	$f->Add('H',4);
	$f->Add('I',8);
	$f->Add('J',1);
	$f->Add('K',1);
	$f->Add('L',4);
	$f->Add('M',2);
	$f->Add('N',5);
	$f->Add('O',8);
	$f->Add('P',2);
	$f->Add('Q',1);
	$f->Add('R',6);
	$f->Add('S',5);
	$f->Add('T',7);
	$f->Add('U',4);
	$f->Add('V',2);
	$f->Add('W',2);
	$f->Add('X',1);
	$f->Add('Y',2);
	$f->Add('Z',1);

	$binary =  HuffmanStream::GetBinaryStream($f,'INFORMATIONISTHES');

	//echo strlen($binary);exit;

	//echo '<br>';

	$base64 = Base64::Encode($binary);
	echo $base64;
	echo '<br>';

	$f->Reset();
	echo HuffmanStream::GetSymbolStream($f,Base64::Decode($base64));



	class HuffmanStream {
		var $frequency_table;
		static public function GetBinaryStream($frequencies,$symbol_stream) {
			$stream = '';
			$length = strlen($symbol_stream);
			for ($index=0; $index<$length; $index++) {
				$codes = Huffman::GetCodes($frequencies->AsTable());
				$symbol = substr($symbol_stream,$index,1);		
				$stream .= $codes[$symbol];
				$frequencies->Adjust($symbol,-1);
			}	
			return $stream;
		}

		static public function GetSymbolStream($frequencies,$binary_stream) {
			$pos = 0;
			$symbol_stream = '';
			$stream_length = strlen($binary_stream);
			while ($pos<$stream_length) {
				$codes = Huffman::GetCodes($frequencies->AsTable());
				$longest = max(array_map('strlen',$codes));
				$lookup_codes = array_flip($codes);

				$found = false;
				for ($length = $longest; $length > 0; $length--) {
					$check_digits = substr($binary_stream,$pos,$length);
					if (isset($lookup_codes[$check_digits])) {
						$symbol_stream .= $lookup_codes[$check_digits];
						$frequencies->Adjust($lookup_codes[$check_digits],-1);
						$pos += $length;
						$found = true;
						break;
					}
				}
				if (!$found) {
					return false;
				}
			}
			return $symbol_stream;
		}
	}
