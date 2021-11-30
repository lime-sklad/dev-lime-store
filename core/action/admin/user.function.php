<?php 
function user_login($login, $pass) {
	$u_data = ls_db_request([
		'table_name' => 'user_control',
		'col_list' => '*',
		'base_query' => '',
		'param' => [
			'query' => [
				'param' => ' WHERE `user_name` = :login ',
				'joins' => '',
				'bindList' => array(
					'login' => $login
				)
			],
			'sort_by' => ' LIMIT 1'
		]
	]);

	if(!empty($u_data)) {
		$this_user = $u_data[0];
	
		if($this_user['user_password'] == $pass) {
			$_SESSION['user'] = $this_user['user_id'];
			$_SESSION['time_start_login'] = time();
			echo json_encode([
				'success' => 'ok'
			]);
			return true;
		} 	
	} 
	echo json_encode([
		'error' => 'Логин или пароль не правильный'
	]);
}


//получить данные пользователя сесии
function getUser($get_info = null) {
	global $dbpdo;
	if(isset($_SESSION['user'])) {
		$user_id = $_SESSION['user'];

		$ustmp = $dbpdo->prepare('SELECT * FROM user_control WHERE user_id = :id');
		$ustmp->bindParam(':id', $user_id, PDO::PARAM_INT);
		$ustmp->execute();
		$row = $ustmp->fetch();
	
		switch ($get_info) {
			case 'get_id':
				return $user_id = $row['user_id'];
				break;
			case 'get_name':
				return $user_name = $row['user_name'];
				break;
			case 'get_role':
				return $user_role = $row['user_role'];
				break;
		}
	} else {
		return null;
	}

}


//получить данные пользователя по id
function get_user_by_id($param) {
	global $dbpdo;
	/**
	* exaple how to use:
	* $param = array(
	* 	'action' => $action,
	* 	'user_id' => $user_id 
	* );
	**/

	$param = (object) $param;
	$action = $param->action;
	$user_id = $param->user_id;

	$ustmp = $dbpdo->prepare('SELECT * FROM user_control WHERE user_id = :id');
	$ustmp->bindParam(':id', $user_id, PDO::PARAM_INT);
	$ustmp->execute();
	$row = $ustmp->fetch();

	switch ($action) {
		case 'get_id': 
			return $user_id = $row['user_id'];
			break;
		case 'get_name':
			return $user_name = $row['user_name'];
			break;
		case 'get_role':
			return $user_role = $row['user_role'];
			break;
		case 'get_reg_date':
			return $reg_date = $row['alert_date'];
			break;	
		case 'get_password':
			return $reg_date = $row['user_password'];
			break;						
	}
}


//обновить данне пользователя
function update_user_info($arr) {
	/**
	* example
	* $arr = array(
	* 	'u_id' => $user_id,
	* 	'action' => 'upd_name',
	*	'param' => $param 
	* );
	*/

	global $dbpdo;

	$user_id 	= $arr['u_id'];
	$action 	= $arr['action'];
	$param 		= $arr['param'];

	switch ($action) {
		case 'upd_name':
			$upd = $dbpdo->prepare('UPDATE user_control SET user_name =:param WHERE user_id =:u_id');
			break;
		case 'upd_pass':
			$upd = $dbpdo->prepare('UPDATE user_control SET user_password =:param WHERE user_id =:u_id');
			break;	
		case 'upd_role':
			$upd = $dbpdo->prepare('UPDATE user_control SET user_role =:param WHERE user_id =:u_id');
			break;	
		case 'upd_vsbl':
			$upd = $dbpdo->prepare('UPDATE user_control SET user_visible =:param WHERE user_id =:u_id');
			break;									
	}
	if(!empty($action)) {
		$upd->bindParam('param', $param);
		$upd->bindParam('u_id', $user_id);
		$upd->execute();
	}

}


//добавляем пользователя в базу
function reg_new_user($dbpdo, $u_name, $u_pass, $u_role, $ordertoday) {
	global $dbpdo;
	$reg_user = $dbpdo->prepare('INSERT INTO user_control (user_id, user_name, user_password, user_role, alert_date) 
		VALUES (NULL, :uname, :upass, :u_role, :cur_date)
	');
	$reg_user->bindValue('uname', $u_name);
	$reg_user->bindValue('upass', $u_pass);
	$reg_user->bindValue('u_role', $u_role);
	$reg_user->bindValue('cur_date', $ordertoday);
	$reg_user->execute();
}

//выводим после регистрации
function render_after_register() {
	$user_id 		= get_last_user('id');
	$user_name 		= get_last_user('name');
	$user_password 	= get_last_user('pass');
	$red_date 		= get_last_user('date');

	$arr = array(
		'u_id' 		 => $user_id,
		'u_name' 	 => $user_name,
		'u_password' => $user_password,
		'u_reg_date' => $red_date 
	);
	ob_start();
	admin_user_list_tr_tpl($arr);
	$res_tpl = ob_get_clean();
	$res = array( 
		'success' => true,
		'append_row' => $res_tpl 
	);
	echo json_encode($res);
}

//выводим данные пользователя после регистрации
function get_last_user($action) {
	global $dbpdo;
	$get_user = $dbpdo->prepare('SELECT * FROM user_control ORDER BY user_id DESC');
	$get_user->execute();
	$row = $get_user->fetch();

	switch ($action) {
		case 'id':
			$user_id = $row['user_id'];
			return $user_id;
			break;
		
		case 'name':
			$user_name 	= $row['user_name'];
			return $user_name;		
			break;
		case 'pass':
			$user_password 	= $row['user_password'];
			return $user_password;		
			break;
		case 'date':
			$red_date  = $row['alert_date'];
			return $red_date;		
			break;						
	}
}

//валидация данных пользователя
function valid_user_info($arr) {
	/** example
	 * $valid_arr = array(
	 * 	'action' => 'valid_name',
	 * 	'param' => ,
	 *	'user_id' => $user_id
	 * );
	 **/
	global $dbpdo;

	$res = false;

	$action = $arr['action'];
	$param = $arr['param'];
	$user_id = $arr['user_id'];

	switch($action) {
		case 'valid_upd_name' : 
			/**
			*проверяем имя пользователя на уникальность по id, если такое имя есть выводим false и сообщение об ошибке
			*если такого имени нет, то выводщим true и обновляем имя пользователя
			*/
			$check_name = $dbpdo->prepare('SELECT * FROM user_control WHERE user_name =:u_name AND user_id !=:u_id');
			$check_name->bindParam('u_name', $param);
			$check_name->bindParam('u_id', $user_id);
			$check_name->execute();
			if($check_name->rowCount()>0) {
				return false;
			} else {
				return true;
			}
		break;
		case 'valid_reg_name' : 
			/**
			*проверяем имя пользователя на уникальность, если такое имя есть выводим false и сообщение об ошибке
			*если такого имени нет, то выводщим true и обновляем имя пользователя
			*/			
			$check_name = $dbpdo->prepare('SELECT * FROM user_control WHERE user_name =:u_name');
			$check_name->bindParam('u_name', $param);
			$check_name->execute();
			if($check_name->rowCount()>0) {
				return false;
			} else {
				return true;
			}
		break;	

		case 'valid_pass': 
			/**
			*очищаем пароль от мусора и проверяем на количество символов, если кол-во смиволов меньше 3х - выводим false и сообщение об ошибке
			*иначе выводим true
			*/			
			$pass = ls_trim($param);
			if(mb_strlen($pass) < 3) {
				return false;
			} else {
				return true;
			}
	}

}
//меняем статус пользовталея на активный и не активный
function update_user_status($args) {
  /**   example
	*	0 - active 
	*	1 - deactive
	*
	*	$args = array(
	*		'status' => '1',
	*		'user_id' => $user_id 
	*	);
	**/  

	global $dbpdo;

	$user_id = $args['user_id'];
	$status  = $args['status'];

	$upd_usr_status = $dbpdo->prepare('UPDATE user_control SET user_visible = :status WHERE user_id = :user_id');
	$upd_usr_status->bindParam('status', $status); 
	$upd_usr_status->bindParam('user_id', $user_id);
	$upd_usr_status->execute();	
}