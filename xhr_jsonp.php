<?php
// The web service returns javascript. Set the Content-Type appropriately
header("Content-Type: application/javascript; charset=utf-8");

if (!empty($_GET['game']) )
{
	define ('HOSTNAME', 'http://api.roar.io/'.$_GET['game'].'/');
	unset( $_GET['game'] );
}
else
{
	define ('HOSTNAME', 'http://api.roar.io/server/');
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

function clean( $arg )
{
      if( get_magic_quotes_gpc() )
      {
        return stripslashes($arg);
      }
      else
      {
        return $arg;
      }
}

$flatget = array();
foreach( $_GET as $k => $v )
{
	if( is_array($v) )
	{
		foreach( $v as $kk => $vv )
		{
			printf( "// %s[%s] = %s\n", $k, $kk, $vv );
			$flatget[ $k.'['.$kk.']' ] =$vv;
		}
	}
	else
	{
		printf( "// %s => %s\n", var_export($k,true), var_export($v,true) );
		$flatget[clean($k)]=clean($v);
	}
} 

curl_setopt ($session, CURLOPT_POSTFIELDS, $flatget);

// Don't return HTTP headers. Do return the contents of the call
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the call
$xml = curl_exec($session);


// Need to escape this xml
echo $callback."(".json_encode($xml).");";
curl_close($session);

?>
