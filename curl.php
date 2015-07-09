<?php

	// URL with GET parameters
	function Curl($url) {
		$curl = curl_init();
		if ($curl===false) {
			echo 'Failed to initialise curl';
			return;
		}
		
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));

		$response = curl_exec($curl);
		curl_close($curl);

		//var_dump($response);

		return $response;
	}