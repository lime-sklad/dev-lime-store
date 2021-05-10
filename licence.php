<?php 

require_once 'db/config.php';


$ordertoday = date("d.m.Y");
$order_myear = date("m.Y");

$deactive_date = date('d.m.Y', strtotime('+30 day'));


if(isset($_POST['licence_key']))
{
	$licence_key = $_POST['licence_key'];
	$licence_owner = $_POST['licence_owner'];

	$licene_md5 = md5($licence_key);

	$update_lincence = $dbpdo->prepare("UPDATE licence 
		SET licence_owner =:licence_owner, 
		licence_active = 1, 
		licence_active_date = :licence_active_date, 
		licence_active_deactive = :licence_active_deactive 
		WHERE licence_value = :licence_key");
	$update_lincence->bindParam('licence_owner', $licence_owner, PDO::PARAM_STR);
	$update_lincence->bindParam('licence_active_date', $ordertoday, PDO::PARAM_INT);
	$update_lincence->bindParam('licence_active_deactive', $deactive_date, PDO::PARAM_INT);
	$update_lincence->bindParam('licence_key', $licene_md5, PDO::PARAM_INT);
	$update_lincence->execute();




	
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Licence</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/fonts/font.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/function.js"></script>
	<script src="js/ajax.js"></script>
	<script src="js/ws.js"></script>
</head>	
</head>
<body>
<style type="text/css">
	body {
		font-family: 'PT Sans Bold', sans-serif;
	}

.main_section_wrapper {
    display: flex;
    width: 100%;
    height: 100%;
    background: #74ebd5;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to left, #ACB6E5, #74ebd5);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to left, #ACB6E5, #74ebd5); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}

.licence_error_msg {
    display: flex;
    width: 100%;
    justify-content: center;
    color: red;
    font-size: 19px;
}

.main_class_container {
    width: 960px;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
}

.content_licnce_i {
    display: flex;
    flex-direction: column;
    padding: 40px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 2px 4px 10px 0px #00000073;
}

.licence_header {
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid #f2f2f2;
}

.licence_header>h1{font-size: 33px;font-weight: 700;display: block;color: #595959;}

.licence_form {
    display: flex;
}

.licence_form_wrp {
    display: flex;
    flex-direction: column;
    width: 574px;
    margin: 0 auto;
    padding: 39px;
}

.licnce_owner_input_wrp {
    width: 100%;
    height: 60px;
    margin: 15px 0px;
    padding: 0px 15px;
    align-items: center;
    display: flex;
    justify-content: space-evenly;
}

.licnce_owner_input_wrp>span {
    display: block;
    padding: 7px 30px;
    font-size: 22px;
    color: #000;
}

.licnce_owner_input {
    display: block;
    border: 1px solid #ddd;
    width: 350px;
    height: 30px;
    font-size: 17px;
    padding: 5px 20px;
    border-radius: 6px;
}

.licence_value_kay_wrp {
    width: 100%;
    height: 60px;
    margin: 15px 0px;
    padding: 0px 15px;
    align-items: center;
    display: flex;
    justify-content: space-evenly;
}

.licence_value_kay_wrp>span {
    display: block;
    padding: 7px 22px;
    font-size: 22px;
    color: #000;
}

.licnce_key_input {
    display: block;
    border: 1px solid #ddd;
    width: 350px;
    height: 30px;
    font-size: 17px;
    padding: 5px 20px;
    border-radius: 6px;
}

.licnce_owner_input:focus {
    box-shadow: 0px 0px 6px 2px #84a7dc;
    outline: transparent;
}

.licnce_key_input:focus {
    box-shadow: 0px 0px 6px 2px #84a7dc;
    outline: transparent;
}

.send_licence_key {
    display: block;
    text-align: right;
    padding: 20px 0px;
}

a.send_licence {
    padding: 7px 30px;
    margin-right: 17px;
    background: #03A9F4;
    color: #fff;
    border-radius: 4px;
    font-size: 18px;
}
		
</style>
	<div class="main_section_wrapper">
		<div class="main_class_container">
			<div class="content_licnce_i">
				<div class="licence_header">
					<h1>Lisenziya müddəti bitdi</h1>
				</div>
				<div class="licence_form">
					<div class="licence_form_wrp">
						<div class="licnce_owner_input_wrp">
							<span>Ad: </span>
							<input type="text" autocomplete="off" name="licnce_owner_input" class="licnce_owner_input">
						</div>
						<div class="licence_value_kay_wrp">
							<span>Açar:</span>
							<input type="text" autocomplete="off" name="licnce_key_input" class="licnce_key_input">
						</div>
						<div class="send_licence_key">
							<a href="javascript:void(0)" class="send_licence">Devam</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>