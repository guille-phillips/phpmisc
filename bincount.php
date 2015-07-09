<html>
<head>
	<style>
	td {
		width:200px;
	}
	</style>
</head>
<body>
<?php

	$values = array();

	$max = 67;

	echo '<table>';
	echo '<tr>';
	for ($n=-1; $n<=$max; $n+=2) {
		echo '<td>'.$n.'</td>';
	}
	echo '</tr>';

	for ($m=1; $m<=$max; $m+=2) {
		echo '<tr><td>'.$m.'</td>';
		for ($n=1; $n<=$max; $n+=2) {
			$s = Ones(decbin($n))+Ones(decbin($m));
			$y = Ones(decbin($n*$m));
			$l = strlen(decbin($n*$m));
			$z = $l-$y;

			echo '<td>';
			if ($y%2==0){			
				//echo $s;
				//echo ':';
				echo decbin($n*$m);
				//$values[$m.'|'.$n] = Ones(decbin($n))+Ones(decbin($m)).':'.Ones(decbin($n*$m));
				//.$values[$m.'|'.$n].
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';



	function Ones($number) {
		$len = strlen($number);

		$total = 0;
		for ($p=0; $p<$len; $p++) {
			$total = $total + substr($number,$p,1);
		}
		return $total;
	}
?>
</body>
</html>