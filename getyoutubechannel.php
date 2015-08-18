<?php
	header('Content-Type: text/plain');

	function CURLGet($url) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));	

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;

	}

	function MapV2toV3($json) {
		$items = $json->items;


		$xml = <<<"XML"
<?xml version='1.0' encoding='UTF-8'?>
<feed xmlns='http://www.w3.org/2005/Atom'
    xmlns:openSearch='http://a9.com/-/spec/opensearch/1.1/'
    xmlns:gml='http://www.opengis.net/gml'
    xmlns:georss='http://www.georss.org/georss'
    xmlns:media='http://search.yahoo.com/mrss/'
    xmlns:batch='http://schemas.google.com/gdata/batch'
    xmlns:yt='http://gdata.youtube.com/schemas/2007'
    xmlns:gd='http://schemas.google.com/g/2005'
    gd:etag='W/&quot;CE4EQH47eCp7ImA9WxRQGEQ.&quot;'>
\t<id>tag:youtube.com,2008:standardfeed:global:most_popular</id>\r\n
XML;

		foreach ($items as $item) {
			$snippet = $item->snippet;
			$title = $snippet->title;
			$description = $snippet->description;

			$xml .= "\t<entry>\r\n";
			$xml .= "\t\t<title>$title</title>\r\n";
			$xml .= "\t</entry>\r\n";
		}

		$xml .= "</feed>\r\n";
		return $xml;
	}

	$json = json_decode(CURLGet('https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=Racingukcom&key=AIzaSyBbQeECnN1g6ez9RRtyuuOxBq5LBGsTbDE'));

	$uploads_playlist_id = $json->items[0]->contentDetails->relatedPlaylists->uploads;


	$json = json_decode(CURLGet("https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=$uploads_playlist_id&key=AIzaSyBbQeECnN1g6ez9RRtyuuOxBq5LBGsTbDE"));

	echo MapV2toV3($json);
	echo '-----------------------------------------------------------------------------';
	print_r($json);

?>