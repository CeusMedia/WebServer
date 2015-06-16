<?php
new \UI_DevOutput();

function htmlize($string) {
	$string	= ltrim($string, "\n");
	$string	= str_replace(array(" ", "\n"), array("&nbsp;", "<br/>"), $string);
	return $string;
}

$get	= htmlize(print_m( $_GET, NULL, NULL, TRUE));
$post	= htmlize(print_m( $_POST, NULL, NULL, TRUE));
$files	= htmlize(print_m( $_FILES, NULL, NULL, TRUE));
$value	= empty( $_POST['input'] ) ? "" : $_POST['input'];

echo '
<html>
	<head>
		<title>Form: plain < PAWS Demo</title>
		<link rel="stylesheet" href="./css/blueprint/reset.css"/>
		<link rel="stylesheet" href="./css/blueprint/typography.css"/>
		<link rel="stylesheet" href="./css/style.css"/>
	</head>
	<body>
		<h2 class="muted"><a href="https://github.com/CeusMedia/WebServer">Ceus Media Web Server</a> Demo</h2>
		<h3>Form Demo: text/plain</h3>
		<form action="form.php5" method="post">
			<label>Input
				<input type="input" name="input" value="'.$value.'"/>
			</label><br/>
			<label>Check
				<input type="checkbox" name="check"/>
			</label><br/>
			<button>send</button>
		</form>
		GET:<br/>
		'.$get.'
		<br/>
		POST:<br/>
		'.$post.'
		<br/>
	</body>
</html>
';
?>
