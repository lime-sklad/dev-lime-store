<?php 
	require $_SERVER['DOCUMENT_ROOT'].'/db/config.php';
	require $_SERVER['DOCUMENT_ROOT'].'/core/function/db.wrapper.php';
	require $_SERVER['DOCUMENT_ROOT'].'/core/function/user.function.php';
	header('Content-type: Application/json');

	if(!empty($_POST['login']) && !empty($_POST['password'])) {
		$login = $_POST['login'];
		$password = $_POST['password'];
		user_login($login, $password);
	} else {
		echo json_encode([
			'error' => 'Заполните все поля'
		]);
	}
