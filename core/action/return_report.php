<?php 

require_once '../../function.php';

//получем список категорий товаров
get_product_category();

header('Content-type: Application/json');



if(isset($_POST['product_count']) && $_POST['product_id'] && $_POST['product_report_id'] ){


	$product_report_id 	= $_POST['product_report_id'];
	$product_id 		= $_POST['product_id'];
	$product_count 		= $_POST['product_count'];

	$get_report_return = $dbpdo->prepare('SELECT * FROM stock_list, stock_order_report
		WHERE stock_order_report.order_stock_id = ?
		AND stock_list.stock_id = stock_order_report.stock_id
	');
	$get_report_return->execute([$product_id]);


	if($get_report_return->rowCount()>0){
	$retunr_report = $get_report_return->fetch();
		//количество в заказе
		$order_stock_count = $retunr_report['order_stock_count'];

		//сбее стоимость товара
		$stock_first_price = $retunr_report['stock_first_price'];

		//цена продажи
		$o_product_price = $retunr_report['order_stock_sprice'];

		//количество товра с стоке
		$stock_count = $retunr_report['stock_count'];

		// //количество в отчете отнимаем от количества возврата 
		// $new_count = $order_stock_count - $product_count;


		// $new_price = $stock_first_price * $new_count;


		// $new_total_price = $o_product_price * $new_count;

		// $total = $new_total_price - $new_price;
		// echo $total;

		//при взоврате есть возможность сделать возврат произвольно кол-во товара
		//то есть если есть заказ с количеством 5 то можно вернуть из них 3 и останеться 2 в отчете
		//тут мы вычитаем из количества заказа то кол-во которое хотим вернуть 
		$o_product_count = $order_stock_count - $product_count;

		//себе стоимость товара умножаем на количество заказа	
		$total_profit = $stock_first_price * $o_product_count;

		//сумма заказа
		$total_order  = $o_product_price * $o_product_count;

		//получем выручку
		$total = $total_order - $total_profit;

		//округляем результат
		$total = round($total, 2);


		$stock_count  = $stock_count + $product_count;

		$return_update_report = $dbpdo->prepare("UPDATE stock_order_report 
			SET order_stock_count = :o_product_count,
			order_total_profit = :total
			WHERE order_stock_id =:stock_return_id");
		$return_update_report->bindParam('stock_return_id', $product_id, PDO::PARAM_INT);
		$return_update_report->bindParam('total', $total, PDO::PARAM_INT);
		$return_update_report->bindParam('o_product_count', $o_product_count, PDO::PARAM_INT);
		$return_update_report->execute();


		//Увеличиваем количество товара на 1 а также менякм значение "возврата" на 1
 		$return_update_stock = $dbpdo->prepare("UPDATE stock_list 
												SET stock_count = :product_count,
												stock_return_status = 1,
												stock_get_fdate = :ordertoday,
												stock_get_year = :order_myear 
												WHERE stock_id = :get_rturn_id");
		$return_update_stock->bindParam('product_count', $stock_count);
		$return_update_stock->bindParam('ordertoday', $ordertoday);
		$return_update_stock->bindParam('order_myear', $order_myear);
		$return_update_stock->bindParam('get_rturn_id', $product_report_id, PDO::PARAM_INT);
		$return_update_stock->execute();		


	$select_count = $dbpdo->prepare('SELECT * FROM stock_order_report
		WHERE order_stock_id = ? AND stock_order_visible = 0');
	$select_count->execute([$product_id]);
	$select_count_row = $select_count->fetch();

	$after_count = $select_count_row['order_stock_count'];

		if($after_count <= 0){
			$success = array('ok' => 'ok' );

			echo json_encode($success);
		} else {
			$success = array('success' => 'success' );

			echo json_encode($success);		
		}

	}
}