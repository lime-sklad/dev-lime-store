<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

if(isset($_POST['modal_order']) && isset($_POST['user_id'])) {
	$user_id = $_POST['user_id'];
	order_admin_user_edit_tpl(array('user_id' => $user_id, 'menu_query' => $menu_query));
}

