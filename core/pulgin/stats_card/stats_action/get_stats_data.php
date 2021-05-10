<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

	header('Content-type: Application/json');


	if(isset($_GET['param'])) {
		if(!empty($_GET['param'])) {

			$tdate = trim($_GET['param']);
			$type = trim($_GET['type']);
			
			//общяя сумма продаж
			$total_circul_money = 0;
			//сумма выручки
			$total_profit = 0;
			//количество продаж
			$total_sales_сount = 0;
			//сумма расхода
			$total_rasxod = 0;


			//карточки для отчета
			//сумма прожадж за меясц
			$stast_arr = [];
			$stats_query = $dbpdo->prepare("
				SELECT * FROM stock_order_report
				WHERE order_my_date = :param
				AND stock_type = :type
				AND stock_order_visible = 0
				AND order_stock_count > 0
				GROUP BY order_stock_id DESC
				ORDER BY order_stock_id DESC");
			$stats_query->bindParam('param', $tdate, PDO::PARAM_INT);
			$stats_query->bindParam('type', $type, PDO::PARAM_STR);
			$stats_query->execute();
			if($stats_query->rowCount()>0) {
				while($stats_row = $stats_query->fetch(PDO::FETCH_BOTH))
					$stast_arr[] = $stats_row;
				foreach ($stast_arr as $stats_row) {
					//получем выручку товара
					$order_total_profit = $stats_row['order_total_profit'];
					//количество товара
					$order_total_count  = $stats_row['order_stock_count'];

					$order_total_not_profit_price = $stats_row['order_stock_total_price'];
						
					$order_price = $stats_row['order_stock_sprice'];

					//добавляем в масив и потом сичитаем общую сумму
					$arr_total_price[] = $order_total_profit;
					//добавляем в массив количестов товара
					$arr_total_count[] = $order_total_count;
					
					$total_circul_money += $order_price * $order_total_count;

				}
					//получаем общую сумму выручки
					$total_profit = round(array_sum($arr_total_price), 2);
					//общее количество товра
					$total_sales_сount = array_sum($arr_total_count);
					
			}


			//расоохды
			$check_total_rasxod = $dbpdo->prepare("SELECT sum(rasxod_money) 
				as total_rasxod_money 
				FROM rasxod 
				WHERE rasxod_year_date = :mydateyear
				AND rasxod_visible = 0");
			$check_total_rasxod->bindParam('mydateyear', $tdate, PDO::PARAM_INT);
			$check_total_rasxod->execute();
			$check_total_rasxod_row = $check_total_rasxod->fetch();
			$total_rasxod = round($check_total_rasxod_row['total_rasxod_money']);


			if($type === 'phone') {
				$total_profit = $total_profit - $total_rasxod;
			}

			else if($type === 'akss') {
				$total_profit = $total_profit;
			}

				$ech = [
					'total_money' => $total_circul_money,
					'total_profit' => $total_profit,
					'total_rasxod' => $total_rasxod,
					'total_sell_count' => $total_sales_сount
				];

				echo json_encode($ech);
			//карточка для склада 

		}
	}

?>