<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/core/pulgin/debt/debt_function.php';
header('Content-type: Application/json');
$customer_id = $_POST['customer_id'];

 
$res = debt_customer_history($customer_id);

var_dump( json_encode($res));