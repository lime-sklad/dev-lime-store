<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: application/json');

//обновить данные пользоватея
if(isset($_POST['user_upd'], $_POST['user_id'], $_POST['user_role']) && !empty($_POST['user_id'] and $_POST['user_role']) and $_POST['user_upd']) {
	$user_id 		= ls_trim($_POST['user_id']);
	$u_name 		= ls_trim($_POST['u_name']);
	$u_pass			= ls_trim($_POST['u_pass']);
    $u_role 		= ls_trim($_POST['user_role']);
	$page_access 	= '';
	$data_access 	= '';	
	

	if(isset($_POST['page_access'])) {
		$page_access 	= $_POST['page_access'];

		//сбрасываем доступы пользователя
		reset_user_all_access(array(
			'id' =>	$user_id,
			'action' => 'page_access',
			'action_state' => true 
		));

		add_user_access_rights(array(
			'action' => 'page_access',
			'data' => $page_access,
			'user_id' => $user_id
		));			
		
	}

	if(isset($_POST['data_access'])) {
		$data_access 	= $_POST['data_access'];

		//сбрасываем доступы пользователя
		reset_user_all_access(array(
			'id' =>	$user_id,
			'action' => 'data_access',
			'action_state' => true 
		));

		add_user_access_rights(array(
			'action' => 'data_access',
			'data' => $data_access,
			'user_id' => $user_id
		));			
		
	}

	//проверка если пользователя с таким именим нет и пароль болдьше 3 символов то выполням и сохраняем
	if( valid_user_info(array('action' => 'valid_upd_name', 'param' => $u_name, 'user_id' => $user_id))
		&&
		valid_user_info(array('action' => 'valid_pass', 'param' => $u_pass, 'user_id' => '')) ) {
		//update_name
		update_user_info(array(
			'u_id' 	 => $user_id, 
			'action' => 'upd_name', 
			'param'  => $u_name
		));

		//update_password
		update_user_info(array(
			'u_id'   => $user_id, 
			'action' => 'upd_pass', 
			'param'  => $u_pass
		));



		//update_user_role
		update_user_info(array(
			'u_id'   => $user_id, 
			'action' => 'upd_role', 
			'param'  => $u_role
		));		
		echo json_encode(array('success' =>  'true'));
	} else {
		echo json_encode(array('error' =>  "Bütün sahələrdə doldurun \n İstifadəçi adınızı yoxlayın, bəlkə də bu istifadəçi adınız məşğuldur"));
	}
}

//деактивировать пользователя
if(isset($_POST['del_user']) && isset($_POST['user_id']) && !empty($_POST['del_user']) && !empty($_POST['user_id'])) {
	
	$user_id = ls_trim($_POST['user_id']);

	//статус пользователя
	update_user_status(array(
		'status' => '1',
		'user_id' => $user_id 
	));

	echo json_encode(array('success' => 'ture'));
}
