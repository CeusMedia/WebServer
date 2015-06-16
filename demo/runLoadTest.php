<?php
(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');
require 'HttpLoadTest.php';

$host	= "localhost";
#$host	= "localhost:81";
$host	= "localhost:8000";


$url	= "/index.html";
#$url	= "/test.mp3";
#$url	= "/index.cm.html";

$uri	= 'http://'.$host.$url;

$numberForks	= 2;
$numberRequests	= 10;
$test	= new \HttpLoadTest($numberForks, $numberRequests);
$test->testUrl($uri);
print PHP_EOL;
?>
