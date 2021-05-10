<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/db/config.php';

$user_list = $dbpdo->prepare("SELECT * FROM user_control");
$user_list->execute();
$user_list_row = $user_list->fetch(PDO::FETCH_BOTH);

$user_name_val = 'value="'.$user_list_row['user_name'].'" ';

if(isset($_POST['btn-login']))
{
 
	if (isset($_POST['email'])) {

		$email = $_POST['email']; 

		 if ($email =='') { 
		 	unset($email);
		 	echo "пароля нет";
		 } 
	}		  

	if (isset($_POST['pass'])) {
		 $pass = $_POST['pass']; 

		 if ($pass =='') { 
		 	unset($pass);
		 	echo "пароля нет";
		 } 
	}


	$ustmp = $dbpdo->prepare('SELECT * FROM user_control WHERE user_name = :email');
	$ustmp->bindParam(':email',$email, PDO::PARAM_STR);
	$ustmp->execute();
	$row = $ustmp->fetch();


	if($row['user_password'] == $pass){
    	$_SESSION['user'] = $row['user_id'];
    	$_SESSION['time_start_login'] = time();
    	header('Location: /core/main/check_files.php'); 
    	exit();     
	}else{
        echo '<a href="login" style="color: red;">Пожалуйста, проверьте правильность написания логина и пароля.</a>';
    }

	
}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="icon" type="image/x-icon" href="img/favicon.ico">	
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />		
	<!-- <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo date('d.m.Y'); ?>"> -->
	<!-- <link rel="stylesheet" type="text/css" href="css/print.min.css"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

	<div class="main_a">		
		<div class="user_login_control">
			<div class="user_logn_wrapper">
				<h2 class="form_title">Giriş</h2>
				<div class="user_login_form">
			        <form method="post">
			            <?php echo @$msg;?>

			                <div class="login_form_row">
			                	<span class="login_input_label">Login</span>
			                    <!-- <input type="text" class="email_input" name="email" placeholder="Your Email" <?php echo $user_name_val ?> required/></td> -->
								<div class="ls-custom-select-wrapper" style="width: 100%">
									<ul class="ls-select-list">
										<div class="select-drop-down">
											<input type="text" id="" name="email" class="email_input drop_down_btn" value="<?php echo $user_list_row['user_name'] ?>" default-value="seçin" >
											<div class="reset_option">
												<div role="button" class="ls-reset-option ls-reset-option-style">
													<i class="button-icon-i las reset-filter-icon la-angle-down"></i>
												</div>
											</div>
										</div>

										<div class="ls-select-option-list">
											<ul class="ls-select-list-option ls-custom-scrollbar">
												<?php 
													$user_list = [];
													$get_user = $dbpdo->prepare('SELECT * FROM user_control');
													$get_user->execute();

													while ($row = $get_user->fetch(PDO::FETCH_BOTH))
														$user_list[] = $row;
													foreach ($user_list as $row) {
													?>

													<li class="ls-select-li">
														<a href="javascript:void(0)" class="choice-option choice-style choice-color " id="<?php echo $row['user_id']; ?>" value="<?php echo $row['user_name'] ?>">
															<span class="mark filter-name"><?php echo $row['user_name']; ?></span>
															<span class="mark filter-mark-text"> </span>
														</a>
													</li>	

												<?php } ?>


											</ul>
										</div>
									</ul>
								</div>
   							 </div>


			                <div class="login_form_row">
			                	<span class="login_input_label">Şifrə</span>
			                    <input type="password" class="email_input" name="pass" placeholder="Your Password" required /></td>
			                </div>
			                <div class="sbm_btn">
			                    <button type="submit" name="btn-login" class="login-submit">Giriş</button></td>
			                </div>
			        </form>					
				</div>
			</div>
		</div>
	</div>
</body>
</html>


<?php include 'include/lib_include.php'; ?>