<?php
$get	= var_export( $_GET, TRUE );
$post	= var_export( $_POST, TRUE );
$files	= var_export( $_FILES, TRUE );

$value	= empty( $_POST['input'] ) ? "" : $_POST['input'];

echo '
<html>
	<head>
		<title>Form: multi < PAWS Demo</title>
		<link rel="stylesheet" href="./css/blueprint/reset.css"/>
		<link rel="stylesheet" href="./css/blueprint/typography.css"/>
		<link rel="stylesheet" href="./css/style.css"/>
	</head>
	<body>
		<h2 class="muted"><a href="https://github.com/CeusMedia/WebServer">Ceus Media Web Server</a> Demo</h2>
		<h3>Form Demo: multipart/form-data</h3>
		<form action="form.php5" method="post" enctype="form-data/multipart">
			<label>Input <input type="input" name="input" value="'.$value.'"/></label><br/>
			<label>File <input type="file" name="file"/></label><br/>
			<button>send</button>
		</form>
		GET:<br/>
		'.$get.'
		<br/>
		POST:<br/>
		'.$post.'
		<br/>
		FILES:<br/>
		'.$files.'
		<br/>
	</body>
</html>
';
?>
