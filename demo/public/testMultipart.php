<?php

$lines = array(
'content-disposition: form-data; name="field1"',
'content-type: text/plain;charset=windows-1250',
'content-transfer-encoding: quoted-printable',
);

$lines = array(
'Content-Disposition: form-data; name="user"; file-name="test.png"',
'Content-Type: image/gif',
'',
'test'
);

echo '<html>';

require_once('HttpHeader.php');
require_once('HttpRequestPart.php');
$part	= new HttpRequestPart($lines);
var_dump( $part->getHeaders() );
var_dump( $part->getContent() );
?>
