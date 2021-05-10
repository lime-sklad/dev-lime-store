<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

ls_include_tpl();

echo get_customer_list($dbpdo, '');



?>