<?php
	header('content-type:text/plain');
	header('cache-control:no-cache,must-revalidate');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

	for ($x=0; $x<3; $x++) {

		echo date("c\n\n");

		flush();
		ob_end_flush();
		
		sleep(3);

		ob_start(null, 0, PHP_OUTPUT_HANDLER_FLUSHABLE | PHP_OUTPUT_HANDLER_REMOVABLE);
	}
?>