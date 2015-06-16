<?php
$url	= 'http://localhost:8000/img/paws_32.jpg';
$curl	= new \Net_CURL($url);
$curl->setOption('CURLOPT_NOBODY', TRUE);
$curl->exec();
new \UI_DevOutput;
ob_start();
print_m($curl->getInfo());
$status	= ltrim(ob_get_clean(), "\n");

echo '
<html>
	<head>
		<title>cURL Test < WebServer @ CeusMedia</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="./css/blueprint/reset.css"/>
		<link rel="stylesheet" href="./css/blueprint/typography.css"/>
		<link rel="stylesheet" href="./css/style.css"/>
	</head>
	<body>
		<h2 class="muted"><a href="https://github.com/CeusMedia/WebServer">Ceus Media Web Server</a> Demo</h2>
		<h3>cURL Head Demo</h3>
		<p>These are the status information returned after a cURL request to an hosted image.</p>
		<xmp>'.$status.'</xmp>
	</body>
</html>
';
?>
