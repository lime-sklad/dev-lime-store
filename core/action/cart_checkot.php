<?php 


$arr = $_POST['res'];



$res = json_decode($arr, true);

foreach ($res as $key => $value) {
	echo $value['name']."<br>";
}