<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
ls_include_tpl();
header('Content-type: application/json');


//если запрос на регистрацию
if(isset($_POST['u_name'], $_POST['u_pass'], $_POST['user_role'])) {
	//очищаем от спец символов 
	$user_name = ls_trim($_POST['u_name']);
	$user_password = ls_trim($_POST['u_pass']);
	$user_role = ls_trim($_POST['user_role']);
	//если данные не пусте
	if(!empty($user_name && $user_password && $user_role)) {

		if( valid_user_info(array('action' => 'valid_reg_name', 'param' => $user_name, 'user_id' => ''))
			&&
			valid_user_info(array('action' => 'valid_pass', 'param' => $user_password, 'user_id' => ''))) {
			//если имя и пароль не пустые, то регистрируем нового пользователя
			reg_new_user($dbpdo, $user_name, $user_password, $user_role, $ordertoday);
			//после регистарции получаем id последнего зарегистрированого пользователя
			$user_id = get_last_user('id');

			//проверяем списко доступа пользователя
			if(isset($_POST['access_page_list'])) {
				$page_access 	= $_POST['access_page_list'];
				add_user_access_rights(array(
					'action' => 'page_access',
					'data' => $page_access,
					'user_id' => $user_id
				));			
			}

			if(isset($_POST['access_data_list'])) {
				$data_access 	= $_POST['access_data_list'];
				add_user_access_rights(array(
					'action' => 'data_access',
					'data' => $data_access,
					'user_id' => $user_id
				));			
			}


			//если все пункты ок выводим пользователя на фронт
			render_after_register();
		} else {
			echo json_encode(array(
				'error' => true,
				'msg' => "Bu ad artıq alınmışdır və ya mövcud deyil. \n Başqa bir istifadəçi adı seçin" 
			));
		}	

	}
}


