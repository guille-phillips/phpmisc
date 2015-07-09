<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<body>
<?php
	include 'huffman.php';


	$freq = array(
		array('ə', '11.49'),
		array('n', '7.11'),
		array('r', '6.94'),
		array('t', '6.91'),
		array('ɪ', '6.32'),
		array('s', '4.75'),
		array('d', '4.21'),
		array('l', '3.96'),
		array('i', '3.61'),
		array('k', '3.18'),
		array('ð', '2.95'),
		array('ɛ', '2.86'),
		array('m', '2.76'),
		array('z', '2.76'),
		array('p', '2.15'),
		array('æ', '2.10'),
		array('v', '2.01'),
		array('w', '1.95'),
		array('u', '1.93'),
		array('b', '1.80'),
		array('e', '1.79'),
		array('ʌ', '1.74'),
		array('f', '1.71'),
		array('aɪ', '1.50'),
		array('ɑ', '1.45'),
		array('h', '1.40'),
		array('o', '1.25'),
		array('ɒ', '1.18'),
		array('ŋ', '0.99'),
		array('ʃ', '0.97'),
		array('y', '0.81'),
		array('g', '0.80'),
		array('dʒ', '0.59'),
		array('tʃ', '0.56'),
		array('aʊ', '0.50'),
		array('ʊ', '0.43'),
		array('θ', '0.41'),
		array('ɔɪ', '0.10'),
		array('ʒ', '0.07')
	);

$freq = array(
	array ('ə', '10.74'),
	array ('ɪ', '8.33'),
	array ('n', '7.58'),
	array ('t', '6.42'),
	array ('d', '5.14'),
	array ('s', '4.81'),
	array ('l', '3.66'),
	array ('ð', '3.56'),
	array ('r', '3.51'),
	array ('m', '3.22'),
	array ('k', '3.09'),
	array ('e', '2.97'),
	array ('w', '2.81'),
	array ('z', '2.46'),
	array ('v', '2'),
	array ('b', '1.97'),
	array ('aɪ', '1.83'),
	array ('f', '1.79'),
	array ('p', '1.78'),
	array ('ʌ', '1.75'),
	array ('eɪ', '1.71'),
	array ('i', '1.65'),
	array ('əʊ', '1.51'),
	array ('h', '1.46'),
	array ('æ', '1.45'),
	array ('ɒ', '1.37'),
	array ('ɔ', '1.24'),
	array ('ŋ', '1.15'),
	array ('u', '1.13'),
	array ('g', '1.05'),
	array ('ʃ', '0.96'),
	array ('j', '0.88'),
	array ('ʊ', '0.86'),
	array ('ɑ', '0.79'),
	array ('aʊ', '0.61'),
	array ('ʤ', '0.6'),
	array ('ɜ', '0.52'),
	array ('ʧ', '0.41'),
	array ('Ɵ', '0.37'),
	array ('eə', '0.34'),
	array ('ɪə', '0.21'),
	array ('oɪ', '0.14'),
	array ('ʒ', '0.1'),
	array ('ʊə', '0.06')
);

	var_dump(Huffman::GetCodes($freq));

?>
</body>
</html>