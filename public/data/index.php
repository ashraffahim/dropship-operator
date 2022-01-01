<?php

$filename = '../../../dropship-seller/data/' . explode('&', $_SERVER['QUERY_STRING'])[0];

if (file_exists($filename)) {

	$quality = isset($_GET['qlt']) ? $_GET['qlt'] : 0;
	$resource = imagecreatefromjpeg($filename);
	$mime_content_type = 'image/jpeg';

	header('Content-type: ' . $mime_content_type);
	echo imagejpeg($resource, null, $quality);
	
} else {

	$resource = '../dopamine/images/illustration/no-photo.png';
	$mime_content_type = 'image/png';
	
	header('Content-type: ' . $mime_content_type);
	echo file_get_contents($resource);

}


?>