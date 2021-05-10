<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

function ls_trim_post($var) {
	$var = trim(strip_tags(stripcslashes(htmlspecialchars($var))));
	return $var;
} 

$name = ls_trim_post($_POST['username']);
$width = ls_trim_post($_POST['width']);
$height = ls_trim_post($_POST['height']);
$msg = ls_trim_post($_POST['msg']);
$quiz_answer = ls_trim_post($_POST['quiz_answer']);


$postRequest = array(
    'username' => $name,
    'width' => $width,
    'height' => $height,
    'msg' => $msg,
    'quiz_answer' => $quiz_answer

);

$cURLConnection = curl_init('https://lime-sklad-api.000webhostapp.com/recive.php');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    'Header-Key: Access-Control-Allow-Origin: *',
    'Header-Key-2: Access-Control-Allow-Credentials: true',
    'Header-Key-2: Access-Control-Allow-Methods: GET, POST, OPTIONS',
    'Header-Key-2: Access-Control-Max-Age: 604800'
));

$apiResponse = curl_exec($cURLConnection);
echo $apiResponse;
curl_close($cURLConnection);


$update_sett = $dbpdo->prepare('UPDATE function_settting SET sett_on = 1 WHERE sett_custom_id = 2');
$update_sett->execute();