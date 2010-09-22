<?php
// The web service returns javascript. Set the Content-Type appropriately
header("Content-Type: application/javascript; charset=utf-8");

if (!empty($_GET['game']) )
{
	define ('HOSTNAME', 'http://roar.io/'.$_GET['game'].'/');
	unset( $_GET['game'] );
}
else
{
	define ('HOSTNAME', 'http://roar.io/lpm/');
}
$callback = $_GET['callback'];
unset($_GET['callback']);


$path = $_GET['path'];
unset($_GET['path']);

$url = HOSTNAME.$path;

// Open the Curl session
echo "// $url\n";
$session = curl_init($url);

// If it's a POST, put the POST data in the body
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $_GET);

// Don't return HTTP headers. Do return the contents of the call
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the call
$xml = curl_exec($session);


// Need to escape this xml
echo $callback."(".json_encode($xml).");";
curl_close($session);

?>
