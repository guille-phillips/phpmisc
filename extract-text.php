<?php
	//header('content-type: text/plain');


	
$opts = array('http' => array('header'=> 
	"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:39.0) Gecko/20100101 Firefox/39.0\r\nCookie: ".$cookie
	)
);

$context = stream_context_create($opts);
$contents = file_get_contents('https://chroma.beanstalkapp.com/repositories', false, $context);
echo $contents;


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

?>