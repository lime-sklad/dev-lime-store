<?php 

require_once '../../function.php';

//получем список категорий товаров
get_product_category();

header('Content-type: Application/json');


//start: добавить расход  
if(isset($_POST['add_rasxod']) 
AND isset($_POST['rasxod_descript'])) {

	if(empty($_POST['add_rasxod']) || empty($_POST['rasxod_descript'])) {
		$success = [
			'error' 	=> 'Bütün sahələri doldurun'
		];	

		echo json_encode($success);
		exit();		
	}

		$get_rasxod_price 		= trim($_POST['add_rasxod']);
		$get_rasxod_descripton  = trim($_POST['rasxod_descript']);


		$insert_rasxod = $dbpdo->prepare("INSERT INTO rasxod 
										(rasxod_id,
										rasxod_day_date,
										rasxod_money,
										rasxod_description,
										rasxod_year_date)
										VALUES
										(NULL, ?,?,?,?)
										");
		$insert_rasxod->execute([
			$ordertoday, 
			$get_rasxod_price, 
			$get_rasxod_descripton, 
			$order_myear
		]);

				
		$rasxod_query = $dbpdo->prepare("SELECT * FROM rasxod WHERE rasxod_visible = 0 GROUP BY rasxod_id DESC");
		$rasxod_query->execute();
		$rasxod_row = $rasxod_query->fetch(PDO::FETCH_BOTH);

		$rasxod_id 				= $rasxod_row['rasxod_id'];
		$rasxod_day_date 		= $rasxod_row['rasxod_day_date'];
		$rasxod_price 			= $rasxod_row['rasxod_money'];
		$rasxod_descriptuon 	= trim($rasxod_row['rasxod_description']);

		$get_rasxod = array(
			'rasxod_id'				=> $rasxod_id, 			   			
			'rasxod_day_date'		=> $rasxod_day_date, 	   	
			'rasxod_price'			=> $rasxod_price, 		   		
			'rasxod_descriptuon' 	=> $rasxod_descriptuon,
			'manat_image'			=> $manat_image 
		);

		$get_table = get_rasxod_tr_tamplate($get_rasxod);		
		
		$success = [
			'success' 	=> 'ok',
			'table'		=> $get_table
		];	

		echo json_encode($success);
		exit();
	
}
//end: добавить расход


if(isset($_POST['rasxod_order'])) {

	$rasxod_id 	= $_POST['rasxod_order'];

	$get_rasxod_info_qry = $dbpdo->prepare("SELECT * FROM rasxod WHERE rasxod_id =:get_info_rasxod AND rasxod_visible = 0");
	$get_rasxod_info_qry->bindParam('get_info_rasxod', $rasxod_id, PDO::PARAM_INT);
	$get_rasxod_info_qry->execute();

	$get_info_row = $get_rasxod_info_qry->fetch();

	$rasxod_id 				= $get_info_row['rasxod_id'];
	$rasxod_day_date 		= $get_info_row['rasxod_day_date'];
	$rasxod_price 			= $get_info_row['rasxod_money'];
	$rasxod_descriptuon 	= $get_info_row['rasxod_description'];

	$get_rsaxod_val = array(
		'rasxod_id' 			 => $rasxod_id,
		'rasxod_vlue'			 => $rasxod_price,
		'rasxod_description'	 => $rasxod_descriptuon 
	);

		$get_table = rasxod_order_tamplate($get_rsaxod_val);		
		
		$success = [
			'table'		=> $get_table
		];	

		echo json_encode($success);
		exit();
}


if(isset($_POST['delete_rasxod']))
{
	if(!empty($_POST['delete_rasxod']))
	{

		$rasxod_id = $_POST['delete_rasxod'];

		$upd_rasxod = $dbpdo->prepare("UPDATE rasxod SET rasxod_visible = 1 WHERE rasxod_id = :rasxod_id");
		$upd_rasxod->bindParam('rasxod_id', $rasxod_id, PDO::PARAM_INT);
		$upd_rasxod->execute();
		$success = [
			'success' => 'ok'
		];	

		echo json_encode($success);
		exit();		
	}
}
