<html>
	<head>
		<title>Request Reflection < PAWS Demo</title>
		<link rel="stylesheet" href="./css/blueprint/reset.css"/>
		<link rel="stylesheet" href="./css/blueprint/typography.css"/>
		<link rel="stylesheet" href="./css/style.css"/>
	</head>
	<body>
		<h2 class="muted"><a href="https://github.com/CeusMedia/WebServer">Ceus Media Web Server</a> Demo</h2>
		<h3>Request Reflection Demo</h3>
		<p>
			This demo reflects all given request parameters.
		</p>

		<h2>Demos</h2>
		<p>
			You can try these demo links or manipulate the URL by yourself.
		</p>
		<ul>
			<li>
				<a href="reflectRequest.php5?"><em>empty</em></a>
			</li>
			<li>
				<a href="reflectRequest.php5?a=1&b=2">a:1 b:2</a>
			</li>
			<li>
				<a href="reflectRequest.php5?keyOnly"><em>key only</em></a>
			</li>
		</ul>

		<h2>Request</h2>
		<xmp><?php var_dump($_REQUEST); ?></xmp>
		<br/>
	</body>
</html>
