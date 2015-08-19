<?php
	header('Content-Type: text/plain');
	ini_set('html_errors', false);

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


		$xml = <<<XML
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
			$thumbnail_url = $snippet->thumbnails->default->url;
			$video_id = $snippet->resourceId->videoId;

			$json_video = json_decode(CURLGet("https://www.googleapis.com/youtube/v3/videos?id=$video_id&part=contentDetails,statistics,suggestions&key=AIzaSyBbQeECnN1g6ez9RRtyuuOxBq5LBGsTbDE"));

			print_r($json_video);

			$duration = $json_video->items[0]->contentDetails->duration;
			$view_count = $json_video->items[0]->statistics->viewCount;
			$favourite_count = $json_video->items[0]->statistics->favoriteCount;
			$author_name = "dunno";

			$xml .= "\t<entry>\r\n";
			$xml .= "\t\t<id>>$video_id</id>\r\n";
			$xml .= "\t\t<gd:rating min='' max='' numRaters='' average='' />\r\n";
			$xml .= "\t\t<yt:statistics viewCount='$view_count' favoriteCount='$favourite_count'>\r\n";
			$xml .= "\t\t<author>\r\n";
			$xml .= "\t\t\t<name>$author_name</name>\r\n";
			$xml .= "\t\t</author>\r\n";
			$xml .= "\t\t<media:group>\r\n";
			$xml .= "\t\t\t<yt:duration seconds='$duration'>\r\n";
			$xml .= "\t\t\t<media:thumbnail url='$thumbnail_url' />\r\n";
			$xml .= "\t\t</media:group>\r\n";
			$xml .= "\t</entry>\r\n";
		}

		$xml .= "</feed>\r\n";
		return $xml;
	}

	$json = json_decode(CURLGet('https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=Racingukcom&key=AIzaSyBbQeECnN1g6ez9RRtyuuOxBq5LBGsTbDE'));

	$uploads_playlist_id = $json->items[0]->contentDetails->relatedPlaylists->uploads;


	$json = json_decode(CURLGet("https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=$uploads_playlist_id&key=AIzaSyBbQeECnN1g6ez9RRtyuuOxBq5LBGsTbDE"));

	print_r(MapV2toV3($json));
	echo "-----------------------------------------------------------------------------\r\n";
	//print_r($json);

?>