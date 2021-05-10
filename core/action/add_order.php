<?php
require_once '../../function.php';

header('Content-type: Application/json');


if( isset($_POST['product_id']) 
AND isset($_POST['product_cont']) 
AND isset($_POST['order_price']) ) {


	//id товара 
	$o_prdoct_id 	 	= 	 trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_id']))));    
	//количество товра
	$o_product_count 	=	 trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_cont']))));
	//цена продажи 
	$o_product_price 	=	 trim(htmlspecialchars(stripcslashes(strip_tags($_POST['order_price']))));

	$order_note         =    trim(htmlspecialchars(stripcslashes(strip_tags($_POST['order_note']))));

	$get_prod_info = $dbpdo->prepare("SELECT * FROM stock_list WHERE stock_id = ?");
	$get_prod_info->execute([$o_prdoct_id]);
	$prod_info = $get_prod_info->fetch(PDO::FETCH_BOTH);

	if($get_prod_info->rowCount()>0) {

		//количество товара в базе
		$stock_count 				= 	$prod_info['stock_count'];
		//себе стоимость товара
		$stock_first_price 			= 	$prod_info['stock_first_price'];
		//imei товара
		$stock_imei 				= 	$prod_info['stock_phone_imei'];
		//имя товра
		$stock_name 				= 	$prod_info['stock_name'];
		//категория товара
		$prodouct_type 				= 	$prod_info['stock_type'];

		$max_count 					= 	$stock_count;

		$get_seller_id =  getUser('get_id');
		//проверяем если количество заказ больше чем количество в базе
		if($o_product_count > $stock_count) {
			$error_message = 'Maksimum say: '.$max_count;

			$error_mgs = [ 'error_notify' => $error_message ];									  								   
			//выводим сообщение и останавливаем выполнение заказа
			echo json_encode($error_mgs);
			exit();
		}

		//если количество пустое
		elseif (empty($o_product_count)) {
			$error_message = 'Minimum say: 1 <br> Bütün sahələri doldurun!';

			$error_mgs = [ 'error_notify' => $error_message ];									  								   
			//выводим сообщение и останавливаем выполнение заказа
			echo json_encode($error_mgs);
			exit();			
		}

		elseif (empty($o_product_price)) {
			$error_message = 'Minimum qiymet: 0';
			
			$error_mgs = [ 'error_notify' => $error_message ];									  								   
			//выводим сообщение и останавливаем выполнение заказа
			echo json_encode($error_mgs);
			exit();				
		}

		//если все данные правильные и
		//если количесто не пустое и не больше чем в базе оформляем заказ
		elseif (!empty($o_product_count) && $o_product_count <= $stock_count && !empty($o_product_price)) {

			//себе стоимость товара умножаем на количество заказа	
			$total_profit = $stock_first_price * $o_product_count;

			//сумма заказа
			$total_order  = $o_product_price * $o_product_count;

			//получем выручку
			$total = $total_order - $total_profit;

			//округляем результат
			$total = round($total, 2);


			//добавляем заказ в базу данных
			$stock_order_report = $dbpdo->prepare("INSERT INTO stock_order_report 
				(order_stock_id, 
				 stock_id, 
				 order_stock_name,
				 order_stock_imei,
				 order_stock_count,
				 order_stock_sprice,
				 order_stock_total_price,
				 order_total_profit, 
				 order_date,order_my_date,
				 order_who_buy,
				 order_real_time,
				 stock_type,
				 order_seller_name) 

				VALUES (NULL, ?,?,?,?,?,?,?,?,?,?,NOW(),?, ?) ");
			$stock_order_report->execute([ $o_prdoct_id, 
										   $stock_name,
										   $stock_imei,
										   $o_product_count, 
										   $o_product_price, 
										   $total_order,
										   $total,
										   $ordertoday,
										   $order_myear,
										   $order_note,
										   $prodouct_type,
										   $get_seller_id
										]);

			//изменяем количество товара в базе
			$stock_list_upd = $dbpdo->prepare(" UPDATE stock_list 
											    SET stock_count=stock_count-:order_count 
											    WHERE stock_id = :order_stock_id
											 ");
			$stock_list_upd->bindParam('order_count', $o_product_count, PDO::PARAM_INT);
			$stock_list_upd->bindParam('order_stock_id', $o_prdoct_id, PDO::PARAM_INT);
			$stock_list_upd->execute();



			//после выпоолнения проаверяем количетво товра
			$check_stock_count = $dbpdo->prepare("SELECT * FROM stock_list WHERE stock_id=:orderId");
			$check_stock_count->bindParam("orderId", $o_prdoct_id, PDO::PARAM_INT);
			$check_stock_count->execute();


			if($check_stock_count->rowCount()>0) {
				$check_stock_count_row = $check_stock_count->fetch();
				$updated_count = $check_stock_count_row['stock_count'];
			}			


			$successs_modal  =	'<div class="order_succes flex-c100" style="display: block;">
									<div class="flex-c100">
										<a href="javascript:void(0)" class="get_print_total" id="'.$o_prdoct_id.'"><img src="img/icon/printer.png"></a>
									</div>
								</div>';
						
			$success_msg = [ 'order_success'	 => $total,
							 'updated_count'	 => $updated_count,
							 'successs_modal'	 => $successs_modal
							];								
			//выводим сообщение и останавливаем выполнение заказа
			echo json_encode($success_msg);
			exit();
		}
	}
}	


//услуги
if(isset($_POST['profit_name']) && isset($_POST['profit_value'])) {

	$product_name = $_POST['profit_name'];
	$product_profit = $_POST['profit_value'];
	$prodouct_type = 'phone';

			//добавляем заказ в базу данных
			$stock_order_report = $dbpdo->prepare("INSERT INTO stock_order_report 
				(order_stock_id, 
				 stock_id, 
				 order_stock_name,
				 order_stock_count,
				 order_stock_sprice,
				 order_stock_total_price,
				 order_total_profit, 
				 order_date,
				 order_my_date,
				 order_real_time,
				 stock_type) 

				VALUES (NULL, 0,?,1,?,?,?,?,?,NOW(),?) ");
			$stock_order_report->execute([ $product_name,
										   $product_profit, 
										   $product_profit,
										   $product_profit,
										   $ordertoday,
										   $order_myear,
										   $prodouct_type
										]);

						
			$success_msg = [ 'ok'	 => 'ok' ];								
			//выводим сообщение и останавливаем выполнение заказа
			echo json_encode($success_msg);
			exit();			

}

