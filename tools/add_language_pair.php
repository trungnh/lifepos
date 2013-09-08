#!/usr/bin/php
<?php
$google_translate_api = 'ABQIAAAA8mQax-UqiuGxCPYp1_723BS9NeWKGxAoxRWyEtnB3qHOEYndYBSdHtMJBqEbVi-x-2W8aBfzJzlfGA';
$languages = array('english' => 'en', 'spanish' => 'es', 'indonesia' => 'id');

fwrite(STDOUT, "Please enter file: ");
$file = trim(fgets(STDIN));

fwrite(STDOUT, "Please enter key: ");
$key = trim(fgets(STDIN));

fwrite(STDOUT, "Please enter english value: ");
$value = trim(fgets(STDIN));

foreach($languages as $folder=>$code)
{
    $path = dirname(__FILE__).'/../application/language/'.$folder.'/'.$file;
    $transaltedValue = translateTo($value, $code);
	$pair = "\$lang['$key'] = '$transaltedValue';";
	file_put_contents($path, str_replace('?>', "$pair\n?>", file_get_contents($path)));
}

exit(0);

function translateTo($value, $language_key)
{
	global $google_translate_api;
	$ip = $_SERVER['REMOTE_ADDR'];
	$value = urlencode($value);
	
	$url = "https://ajax.googleapis.com/ajax/services/language/translate?" .
       "v=1.0&q=$value&langpair=en%7C$language_key&key=$google_translate_api&userip=$ip";
	// sendRequest
	// note how referer is set manually
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, 'http://muench.homeip.net');
	$body = curl_exec($ch);
	curl_close($ch);

	// now, process the JSON string
	$json = json_decode($body);
	// now have some fun with the results..
	
	return $json->responseData->translatedText;
}
?>