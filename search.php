<?php 

	require_once 'function.php';

	ls_include_tpl();
	

	if (isset($_POST['search_item_value']) && isset($_POST['search_from'])){
		
		//вызываем функцию типы таблиц (если не ясно посмотри комментарий в function.php)
		get_table_svervice_type();


		//получаем списко категорий товаров 
		//$product_phone телефоны
		//$product_akss  акссесуары
		get_product_category();

		$search_val  		= trim(strip_tags(htmlspecialchars($_POST['search_item_value'])));  //получаем текст поиска
		$search_table_type  = trim(strip_tags(htmlspecialchars($_POST['search_from'])));		//тип поиска (терминал, склад и тд)
		$search_product_cat = trim(strip_tags(htmlspecialchars($_POST['search_product_cat'])));			//категория товара (акссеаупаы, телефоны)
		
		$search_value_stmt = $dbpdo->prepare('SELECT * FROM stock_list 
								WHERE stock_name 
								LIKE :search_val_name 
								AND stock_visible = 0
								AND stock_type = :name_prod_cat 
								AND stock_count > 0 
								
								OR stock_phone_imei 
								LIKE :search_val_imei  
								AND stock_count > 0 
								AND stock_type = :imei_prod_cat
								AND stock_visible = 0 

								OR stock_provider 
								LIKE :search_val_provider 
								AND stock_visible = 0
								AND stock_type = :provider_prod_cat
								AND stock_count > 0

								OR stock_get_fdate 
								LIKE :search_val_fdate 
								AND stock_visible = 0
								AND stock_type = :fdate_prod_cat
								AND stock_count > 0

								GROUP BY stock_id DESC');
		$search_value_stmt->bindValue('search_val_name', 	  "%{$search_val}%"); 
		$search_value_stmt->bindValue('search_val_imei', 	  "%{$search_val}%"); 
		$search_value_stmt->bindValue('search_val_provider',  "%{$search_val}%"); 
		$search_value_stmt->bindValue('search_val_fdate', 	  "%{$search_val}%");

		$search_value_stmt->bindValue('name_prod_cat', 	  	$search_product_cat);
		$search_value_stmt->bindValue('imei_prod_cat', 	  	$search_product_cat);
		$search_value_stmt->bindValue('provider_prod_cat',  $search_product_cat);
		$search_value_stmt->bindValue('fdate_prod_cat', 	$search_product_cat);

		$search_value_stmt->execute();
		$res = [];

		if($search_value_stmt->rowCount() > 0) {
			while ($search_value_row = $search_value_stmt->fetch(PDO::FETCH_BOTH))
				$search_list[] = $search_value_row;
				foreach ($search_list as $search_value_row) {

					//общие данные
					$stock_id 				= $search_value_row['stock_id'];
					$stock_name 			= $search_value_row['stock_name'];
					$stock_first_price 		= $search_value_row['stock_first_price'];
					$stock_second_price 	= $search_value_row['stock_second_price'];
					$stock_provider		 	= $search_value_row['stock_provider'];
					$stock_date             = $search_value_row['stock_get_fdate'];
					$return_image           = '';

					//телефоны
					$stock_imei 			= $search_value_row['stock_phone_imei'];
					$stock_get_date 		= $search_value_row['stock_get_fdate'];
					$stock_return_status 	= $search_value_row['stock_return_status'];
					if($stock_return_status == 1) { $stock_return = $stock_return_image; } else { $stock_return = ''; }

					//акссеуары
					$stock_count 			= $search_value_row['stock_count'];
					$stock_date 			= $search_value_row['stock_get_fdate'];
					
					//тут проверяем на вклдаку терминала
					if($search_table_type == $terminal) {
						//проверяем товар на категорию телефон
						if($search_product_cat == $product_phone) {

							echo '<tr class="stock-list" id="'.$stock_id.'">';		
								check_td_access_tpl(array(
									'th_serial' 	=> $stock_id,
									'th_prod_name'  => $stock_name,
									'th_imei'		=> $stock_imei,
									'th_buy_price'  => $stock_first_price,
									'th_sale_price' => $stock_second_price,
									'th_provider'   => $stock_provider,
									'th_return'		=> $stock_return
								));
							echo '</tr>';	

						} 

						//проверяем товар на категорию акссесуар
						if($search_product_cat == $product_akss) {
							echo '<tr class="stock-list" id="'.$stock_id.'">';		
								check_td_access_tpl(array(
									'th_serial' 	=> $stock_id,
									'th_prod_name'  => $stock_name,
									'th_buy_price'  => $stock_first_price,
									'th_sale_price' => $stock_second_price,
									'th_count'      => $stock_count,
									'th_category'   => $stock_provider 
								));
							echo '</tr>';	
						}
					}


					//если поиск по складу
					if($search_table_type == $stock) {
						//если телефон в складе
						if($search_product_cat == $product_phone) {
							echo '<tr class="stock-list" id="'.$stock_id.'">';		
								check_td_access_tpl(array(
									'th_serial' 	=> $stock_id,
									'th_buy_day'	=> $stock_date,
									'th_prod_name'  => $stock_name,
									'th_imei'		=> $stock_imei,
									'th_buy_price'  => $stock_first_price,
									'th_sale_price' => $stock_second_price,
									'th_provider'   => $stock_provider,
									'th_return'		=> $stock_return
								));
							echo '</tr>';	

						}

						if($search_product_cat == $product_akss) {
							echo '<tr class="stock-list" id="'.$stock_id.'">';		
								check_td_access_tpl(array(
									'th_serial' 	=> $stock_id,
									'th_buy_day'	=> $stock_date,
									'th_prod_name'  => $stock_name,
									'th_buy_price'  => $stock_first_price,
									'th_sale_price' => $stock_second_price,
									'th_count'		=> $stock_count,
									'th_provider'   => $stock_provider
								));
							echo '</tr>';		
						}
					}

		}

		//показываем количество и цену товара по категории в скаде
		if($search_table_type == $stock) echo get_product_count_price($search_val, $search_product_cat, $manat_image);
		if($search_table_type == $terminal && $search_product_cat == $product_phone) echo get_total_search_some($search_value_stmt);

	} 
	//если поиск по отчету
	if($search_table_type == $report) {
		$product_category = $search_product_cat;



		if(isset($_POST['sort_data'])){
			$sort_product = trim($_POST['sort_data']);
			//СОРТИРОВКА ПО ИМЕНИ 
			if($sort_product == 'name') {
				$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod

				INNER JOIN stock_order_report
				ON stock_order_report.order_stock_name = :search_name
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category
				AND stock_order_report.order_stock_count > 0

				LEFT JOIN stock_list 
				ON stock_list.stock_id = stock_order_report.stock_id 			
				
		 		GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_stock_id DESC");
				$report_stmt->bindValue('search_name', $search_val);
			}
			if($sort_product == 'imei') {
				$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod

				INNER JOIN stock_order_report
				ON stock_order_report.order_stock_imei = :search_name
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category
				AND stock_order_report.order_stock_count > 0

				LEFT JOIN stock_list 
				ON stock_list.stock_id = stock_order_report.stock_id 			
				
		 		GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_stock_id DESC");
				$report_stmt->bindValue('search_name', $search_val);
			}
			//СОРТИРОВКА ПО ДАТЕ
			if($sort_product == 'date') {
				$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod

				INNER JOIN stock_order_report
				ON stock_order_report.order_date
				LIKE :search_query
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category	
				AND stock_order_report.order_stock_count > 0

				LEFT JOIN stock_list 
				ON stock_list.stock_id = stock_order_report.stock_id 			
				
		 		GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_stock_id DESC");
				$report_stmt->bindValue('search_query',  "%{$search_val}%"); 				
			}

			//СОРТИРОВКА ПО ДАТЕ
			if($sort_product == 'full_date') {
				$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod

				INNER JOIN stock_order_report
				ON stock_order_report.order_my_date
				LIKE :search_query
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category	
				AND stock_order_report.order_stock_count > 0

				LEFT JOIN stock_list 
				ON stock_list.stock_id = stock_order_report.stock_id 			
				
		 		GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_stock_id DESC");
				$report_stmt->bindValue('search_query',  "%{$search_val}%"); 				
			}
			//сортировка по категоии/provider
			if($sort_product == 'provider') {
				$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod

				LEFT JOIN stock_list 
				ON stock_list.stock_provider
				LIKE :search_query 

				INNER JOIN stock_order_report
				ON stock_order_report.stock_id = stock_list.stock_id
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category	
				AND stock_order_report.order_stock_count > 0
		 		GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_stock_id DESC");		
				$report_stmt->bindValue('search_query',  "%{$search_val}%"); 					
			}
		} if(empty($_POST['sort_data'])) {	
				$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod

				INNER JOIN stock_order_report
				ON stock_order_report.order_stock_name = :search_query
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category
				AND stock_order_report.order_stock_count > 0

				OR 	stock_order_report.order_stock_imei 
				LIKE :search_query_imei
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category_second
				AND stock_order_report.order_stock_count > 0

				LEFT JOIN stock_list 
				ON stock_list.stock_id = stock_order_report.stock_id 			
				
		 		GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_stock_id DESC");	
				$report_stmt->bindValue('search_query',  $search_val); 
				$report_stmt->bindValue('search_query_imei',  "%{$search_val}%"); 
				$report_stmt->bindValue('prod_category_second',  $search_product_cat);	
		}

		if(empty($search_val)) {
			$search_val = $order_myear;
				$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod

				INNER JOIN stock_order_report
				ON stock_order_report.order_my_date
				LIKE :search_query
				AND stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :prod_category	
				AND stock_order_report.order_stock_count > 0

				LEFT JOIN stock_list 
				ON stock_list.stock_id = stock_order_report.stock_id 			
				
		 		GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_stock_id DESC");	
				$report_stmt->bindValue('search_query',  "%{$search_val}%"); 		
		}

		$report_list = [];
		$report_stmt->bindValue('prod_category',  $search_product_cat);
		$report_stmt->execute();

		   if($report_stmt->rowCount() > 0){
				while ($report_row = $report_stmt->fetch(PDO::FETCH_BOTH))
					$report_list[] = $report_row;
					foreach ($report_list as $report_row)
					{
						$stock_id 			= $report_row['order_stock_id'];
						$order_date 		= $report_row['order_date'];
						$order_mydate 		= $report_row['order_my_date'];
						$stock_name 		= $report_row['order_stock_name'];
						$stock_imei 		= $report_row['order_stock_imei'];
						$stock_sprice 		= $report_row['order_stock_sprice'];
						$stock_provider 	= $report_row['stock_provider'];
						$stock_count 		= $report_row['order_stock_count'];
						$order_note 		= $report_row['order_who_buy'];
						$stock_profit 		= $report_row['order_total_profit'];

						//если телефон
						if($search_product_cat == $product_phone) {
							echo '<tr class="stock-list" id="'.$stock_id.'">';		
								 check_td_access_tpl(array(
									'th_serial' 		=> $stock_id,
									'th_day_sale'		=> $order_date,
									'th_prod_name' 	  	=> $stock_name,
									'th_imei'			=> $stock_imei,
									'th_sale_price' 	=> $stock_sprice,
									'th_provider' 		=> $stock_provider,
									'th_report_note'	=> $order_note,
									'th_count'			=> $stock_count,
									'th_profit'			=> $stock_profit
								));										
							echo '</tr>';									
						}

						//если акссесуар
						if($search_product_cat == $product_akss) {
							echo '<tr class="stock-list" id="'.$stock_id.'">';		
								 check_td_access_tpl(array(
									'th_serial' 		=> $stock_id,
									'th_day_sale'		=> $order_date,
									'th_prod_name' 	  	=> $stock_name,
									'th_sale_price' 	=> $stock_sprice,
									'th_provider' 		=> $stock_provider,
									'th_report_note'	=> $order_note,
									'th_count'			=> $stock_count,
									'th_profit'			=> $stock_profit
								));										
							echo '</tr>';								
						}
					}
				get_total_all_profit_phone($dbpdo, $search_val, $product_category,$manat_image);	
			} 

	} 


	//поиск по блокноту и расходу
	if($search_table_type == $note) {
		$new_order_list = [];
		
		if(isset($_POST['sort_data'])){
			$order_stock_view = $dbpdo->prepare("SELECT * FROM no_availible_order 
				WHERE order_stock_date = :search3
				AND order_stock_visible = 0 
				AND note_type = :note_type3	

				GROUP BY order_stock_id DESC");			
			$order_stock_view->bindValue('search3', $search_val, PDO::PARAM_INT);
			$order_stock_view->bindValue('note_type3', $search_product_cat, PDO::PARAM_INT);
		} else {
			$order_stock_view = $dbpdo->prepare("SELECT * FROM no_availible_order 
				WHERE order_stock_name LIKE :search1
				AND order_stock_visible = 0 
				AND note_type = :note_type

				OR order_stock_description LIKE :search2
				AND order_stock_visible = 0 
				AND note_type = :note_type2	

				GROUP BY order_stock_id DESC");
			$order_stock_view->bindValue('search1', "%{$search_val}%");
			$order_stock_view->bindValue('search2', "%{$search_val}%");
			$order_stock_view->bindValue('note_type', $search_product_cat);
			$order_stock_view->bindValue('note_type2', $search_product_cat);
		}
		if(empty($search_val)) {
			$search_val = $order_myear;

			$order_stock_view = $dbpdo->prepare("SELECT * FROM no_availible_order 
				WHERE order_stock_date = :search3
				AND order_stock_visible = 0 
				AND note_type = :note_type3	

				GROUP BY order_stock_id DESC");			
			$order_stock_view->bindValue('search3', $order_myear);
			$order_stock_view->bindValue('note_type3', $search_product_cat);
		} 


		$order_stock_view->execute();			
		while ($order_stock_row = $order_stock_view->fetch(PDO::FETCH_BOTH))
			$new_order_list[] = $order_stock_row;
		foreach ($new_order_list as $order_stock_row)
		{	
				$note_id 			= $order_stock_row['order_stock_id'];
				$note_date 			= $order_stock_row['order_stock_full_date'];
				$note_name 			= $order_stock_row['order_stock_name'];
				$note_descrpt 		= $order_stock_row['order_stock_description'];

				$get_note = array(
					'note_id' 		=> $note_id,
					'note_date' 	=> $note_date, 			
					'note_name' 	=> $note_name, 			
					'note_descrpt'  => $note_descrpt 	 	
				);

				echo get_note_list($get_note);

		}

	}


	//фильтр по расходу
	if($search_table_type == $rasxod) {

		if(isset($_POST['sort_data'])) {

			$rasxod_arr = [];
			$filter = $_POST['sort_data'];

			//поиск по дате 

			if($filter == 'full_date') {
				$rasxod_query = $dbpdo->prepare('
					SELECT * FROM rasxod
					WHERE rasxod_year_date LIKE :search1
					AND rasxod_visible = 0
				');
			}

			$rasxod_query->bindValue('search1', "%{$search_val}%");
			$rasxod_query->execute();

			while($rasxod_row = $rasxod_query->fetch(PDO::FETCH_BOTH))
				$rasxod_arr[] = $rasxod_row;
			foreach($rasxod_arr as $rasxod_row) {
				$rasxod_id 				= $rasxod_row['rasxod_id'];
				$rasxod_day_date 		= $rasxod_row['rasxod_day_date'];
				$rasxod_price 			= $rasxod_row['rasxod_money'];
				$rasxod_descriptuon 	= $rasxod_row['rasxod_description'];								
				
				$get_rasxod = array(
					'rasxod_id'				=> $rasxod_id, 			   			
					'rasxod_day_date'		=> $rasxod_day_date, 	   	
					'rasxod_price'			=> $rasxod_price, 		   		
					'rasxod_descriptuon' 	=> $rasxod_descriptuon,
					'manat_image'			=> $manat_image 
				);

				echo get_rasxod_tr_tamplate($get_rasxod);			

			}

			get_total_rasxod_value( $search_val );

		}

	}


}
?>