<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/db/config.php';

/******CHECK FILTER TABLE NAME******/
$filter_tbname = 'filter';

$filter_tb_sql = 'CREATE TABLE `filter` (
				  `filter_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `filter_type` varchar(255) NOT NULL,
				  `filter_value` varchar(255) NOT NULL,
				  `filter_visible` int(11) NOT NULL DEFAULT 0
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

$filter_insert_sql = '';
//это было в версии 0.06
$filter_data = array(
					array("1", "Black"),
					array("1", "Red"),
					array("1", "Gold"),
					array("1", "Rose Gold"),
					array("1", "Rose"),
					array("1", "Yellow"),
					array("1", "Pink"),
					array("1", "Gray"),
					array("1", "Blue"),

					array("2", "2"),
					array("2", "4"),
					array("2", "8"),
					array("2", "16"),
					array("2", "32"),
					array("2", "64"),
					array("2", "128"),
					array("2", "512"),
					
					array("3", "1"),
					array("3", "2"),
					array("3", "3"),
					array("3", "4"),
					array("3", "8"),
					array("3", "16"),
					array("3", "32"),
					array("3", "64"),

					array("4", "işlənmiş"),
					array("4", "Yeni"),

					array("5", "Samsung"),
					array("5", "Apple"),
					array("5", "Xiomi"),
					array("5", "Mi")
					
				);
//fix 0.06/7 название таблицы, [название столбца], [данне которые нужно найти и заменить на...] 
update_column_data('filter', ['filter_type'],  ['color' => '1', 'storage' => '2', 'ram' => '3', 'used' => '4'] );
//создаем таблицу и заполняем ее 30.08
check_table_exists($filter_tbname, $filter_tb_sql, $filter_data);


//добавляю к таблице новые столбцы
// $filter_column_name = array(
// 	array("col_name",	 "int(11) NOT NULL"),
// );
//дополнить таблицу столбцами
// check_table_column_exists($filter_tbname, $filter_column_name);		
/******END FILTER******************/




/******CHECK STOCK_FILTER TABLE NAME******/
$stock_filter_tbname = 'stock_filter';
$stock_filter_sql = "CREATE TABLE `stock_filter` (
					  `stock_filter_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
					  `stock_id` int(11) NOT NULL,
 					  `active_filter_id` int(11) NOT NULL,
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$filter_data = '';
$stock_filter_column_list = array(
 	array("active_filter_id", "int(11) NOT NULL")
);


//создаем таблицу и заполняем ее 30.08
check_table_exists($stock_filter_tbname, $stock_filter_sql, $filter_data);
check_table_column_exists($stock_filter_tbname, $stock_filter_column_list);
custom_sql($stock_filter_tbname);

//в версии 0.08 я сменил структуру таблицы фильтров и поэтому, обновляем его тут

//добавляю к таблице новые столбцы
 // $stock_filter_column_name = array(
 // 	array("column name", "column param"),
 // );

//дополнить таблицу столбцами
//check_table_column_exists($stock_filter_tbname, $stock_filter_column_name);


$filter_list_name = 'filter_list';
$filter_list_db_sql = "CREATE TABLE `filter_list` (
 `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
 `filter_list_id` int(11) NOT NULL,
 `filter_list_prefix` varchar(255) COLLATE utf8_bin NOT NULL,
 `filter_list_title` varchar(255) COLLATE utf8_bin NOT NULL,
 `filter_short_name` varchar(255) COLLATE utf8_bin NOT NULL,
 `filter_list_visible` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
$filter_list_data = array(
						array("1", "color", "Rəng", ""), 
						array("2", "storage", "Yaddaş", "GB"), 
						array("3", "ram", "Ram", "GB"), 
						array("4", "used", "Yeni/İşlənmiş", ""),
						array("5", "brand", "Brend", "")
					);
			
check_table_exists($filter_list_name, $filter_list_db_sql, $filter_list_data);

/******END STOCK_FILTER******************/


$quiz_sett_name = 'function_settting';
$quiz_sett_db = '';
$quiz_sett_data = array(
					array("2", "get_quiz_theme"),
				);
//создаем таблицу и заполняем ее 15.09
check_db_data($quiz_sett_name, $quiz_sett_data);



/**добавляю к таблице товаров новую сроку**/
$stock_list_barcode_db_name = 'stock_list';
$stock_list_barcode_colum_arr = array(
	array("stock_type", "varchar(255) NOT NULL DEFAULT 'phone'"),
	array("barcode_article", "VARCHAR(255) NOT NULL"),
	array("product_added", " INT NOT NULL DEFAULT 1"),
	array("product_provider", "INT NOT NULL"),
	array("product_category", "INT NOT NULL"),
	array("min_quantity_stock", "INT NOT NULL DEFAULT '1' ")
);
check_table_column_exists($stock_list_barcode_db_name, $stock_list_barcode_colum_arr);


/**конец*/

// user_control
$user_control_db_nme = 'user_control';
$user_control_admin_column_arr = array(
	array("user_role", "VARCHAR(255) NOT NULL DEFAULT 'admin' "),
	array("user_visible", "int(11) NOT NULL DEFAULT 0"),
	array("alert_date", "VARCHAR(255) NOT NULL")
);
check_table_column_exists($user_control_db_nme, $user_control_admin_column_arr);
//end

//stock_order_report add seller column
$stock_order_report_db_name = 'stock_order_report';
$stock_order_rep_column_name = array(
	array('stock_type', "varchar(255) NOT NULL DEFAULT 'phone'"),
	array('order_seller_name', ' VARCHAR(255) NOT NULL') 
);
check_table_column_exists($stock_order_report_db_name, $stock_order_rep_column_name);
//end


//start th_list шаблоны названий таблиц
$th_list = 'th_list';
$th_list_sql = "CREATE TABLE `th_list` (
				 `th_id` int(11) NOT NULL AUTO_INCREMENT,
				 `th_description` varchar(255) COLLATE utf8_bin NOT NULL,
				 `th_name` varchar(255) COLLATE utf8_bin NOT NULL,
				 PRIMARY KEY (`th_id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin";

$th_list_data = array(
					array("th_serial",		 		"№"),
					array("th_prod_name", 			"Malın adı"),
					array("th_imei", 				"IMEI"),
					array("th_buy_price", 			"Alış qiyməti"),
					array("th_sale_price", 			"Qiymət"),
					array("th_provider", 			"Təchizatçı"),
					array("th_return", 				"Vazvrat"),
					array("th_count", 				"Sayı"),
					array("th_category", 			"Kategoriya"),
					array("th_buy_day", 			"Alış günü"),
					array("th_day_sale", 			"Satış günü"),
					array("th_report_note", 		"Qeyd"),
					array("th_profit", 				"Xeyir"),
					array("th_total_circ_money", 	"Ümumi dövriyyə"),
					array("th_rasxod", 			  	"Xarc"),
					array("th_report_serial", "№"),
					array("th_serial", "№"),
					array("th_description", "Təsvir"),


					array("th_admin_password", "Şifrə"),
				);
//создаем таблицу и заполняем ее 30.08
check_table_exists($th_list, $th_list_sql, $th_list_data);
//end th_list 

$data_td_accsess = 'data_td_accsess';
$data_td_accesss_sql = 'CREATE TABLE `data_td_accsess` (
						 `td_id` int(11) NOT NULL AUTO_INCREMENT,
						 `user_id` int(11) NOT NULL,
						 `td_tags_id` int(11) NOT NULL,
						 `accsess_status` tinyint(4) NOT NULL DEFAULT 0,
						 PRIMARY KEY (`td_id`)
						) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin';
$data_td_acess_list_data = '';
//создаем таблицу и заполняем ее 30.08
check_table_exists($data_td_accsess, $data_td_accesss_sql, $data_td_acess_list_data);						


$user_accesss_page = 'user_access_pages';
$user_accesss_page_sql = 'CREATE TABLE `user_access_pages` (
							 `access_id` int(11) NOT NULL AUTO_INCREMENT,
							 `user_id` int(11) NOT NULL,
							 `access_page_name` varchar(255) COLLATE utf8_bin NOT NULL,
							 `access_page_base_link` varchar(255) COLLATE utf8_bin NOT NULL,
							 PRIMARY KEY (`access_id`)
							) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin';
$user_accesss_page_data_list = '';
check_table_exists($user_accesss_page, $user_accesss_page_sql, $user_accesss_page_data_list);							

/** provider */
$provider_table_name = 'stock_provider';
$provider_sql = "CREATE TABLE `stock_provider` ( 
					`provider_id` INT NULL AUTO_INCREMENT , 
					`provider_name` VARCHAR(255) NOT NULL , 
					`visible` VARCHAR(255) NOT NULL DEFAULT 'visible' , 
					 PRIMARY KEY (`provider_id`)
				) ENGINE = InnoDB;";

$get_stock_provider_list = $dbpdo->prepare("SELECT DISTINCT stock_provider FROM stock_list WHERE stock_visible = 0 AND stock_type = 'phone' ");
$get_stock_provider_list->execute();
$provider_list = $get_stock_provider_list->fetchAll(PDO::FETCH_COLUMN);
check_table_exists($provider_table_name, $provider_sql, $provider_list);


$stock_category_name = 'stock_category';
$stock_category_sql = "CREATE TABLE `stock_category` ( 
	`category_id` INT NULL AUTO_INCREMENT , 
	`category_name` VARCHAR(255) NOT NULL , 
	`visible` VARCHAR(255) NOT NULL DEFAULT 'visible' , 
	 PRIMARY KEY (`category_id`)
) ENGINE = InnoDB;";

$get_stock_category_list = $dbpdo->prepare("SELECT DISTINCT stock_provider FROM stock_list WHERE stock_visible = 0 AND stock_type = 'akss' ");
$get_stock_category_list->execute();
$stock_category_list = $get_stock_category_list->fetchAll(PDO::FETCH_COLUMN);
check_table_exists($stock_category_name, $stock_category_sql, $stock_category_list);

// check_table_exists()

//проверяю на наличие таблицы в базе данных
function check_table_exists($table_name, $sql, $data) {
	global $dbpdo;
	//проверка на таблицу 
	try {	
		$check_db = $dbpdo->prepare('SELECT EXISTS(
	       SELECT * FROM  '.$table_name.') ');
		$check_db->execute();
		//если есть таблица проверить на дату и добавить дату
		check_db_data($table_name, $data);
	} 
	catch(PDOException $e) {
		//если таблицы нет
		creat_table($table_name, $sql, $data);
	}
}

//создаю новую табицу в базе данных
function creat_table($table_name, $sql, $data) {
	global $dbpdo;
	try { 
	    $dbpdo->exec($sql);
	    check_db_data($table_name, $data);
	} 
	catch(PDOException $e) {
	    // header('Location: /');
		// exit();
	}
}

//если таблица была в базе проверяю ее на пустоту
function check_db_data($table_name, $array) {
	global $dbpdo;

	$data_arr = array();

	//START таблица filter
	if($table_name == 'filter') {
		foreach ($array as $row => $value) {
			$filter_type = $value[0];
			$filter_value = $value[1];
			try {
				$check_exist_data = $dbpdo->prepare('SELECT * FROM filter
				WHERE filter_type = :type 
				AND filter_value = :value 
				GROUP BY filter_id DESC');
				$check_exist_data->bindParam('type', $filter_type);	
				$check_exist_data->bindParam('value', $filter_value);
				$check_exist_data->execute();

				if($check_exist_data->rowCount()>0) {
					echo " ";
				} else {
					$data_arr = array(
						'filter_type' => $filter_type,
						'filter_value' => $filter_value
					);

					install_data( $table_name, $data_arr );
				} 

			} catch(PDOException $e) {
				echo  " таблицы не существует";
			}
		}	
	}

	if($table_name === 'function_settting') {
		foreach ($array as $row => $value) {
			$sett_id = $value[0];
			$sett_name = $value[1];
			try {
				$check_exist_data = $dbpdo->prepare('SELECT * FROM function_settting
				WHERE sett_name = :sett_name 
				GROUP BY sett_id DESC');
				$check_exist_data->bindParam('sett_name', $sett_name);
				$check_exist_data->execute();

				if($check_exist_data->rowCount()>0) {
					echo " ";
				} else {
					$data_arr = array(
						'sett_id' => $sett_id,
						'sett_name' => $sett_name
					);

					install_data( $table_name, $data_arr );
				} 

			} catch(PDOException $e) {
				echo " таблицы не существует";
			}
		}		
	}
	//END таблица filter


	//start th_list
	if($table_name === 'th_list') {
		foreach ($array as $row => $value) {
			$th_description = $value[0];
			$th_name = $value[1];
			try {
				$check_exist_data = $dbpdo->prepare('SELECT * FROM th_list
				WHERE th_description = :th_description 
				GROUP BY th_id DESC');
				$check_exist_data->bindParam('th_description', $th_description);
				$check_exist_data->execute();

				if($check_exist_data->rowCount()>0) {
					echo " ";
				} else {
					$data_arr = array(
						'th_description' => $th_description,
						'th_name' => $th_name
					);

					install_data( $table_name, $data_arr );
				} 

			} catch(PDOException $e) {
				echo " таблицы не существует 1 </pre>";
			}
		}
	}
	//end th_list

	//start filter_list
	if($table_name === 'filter_list') {
		foreach ($array as $row => $value) {
			$prefix_id = $value[0];
			$filter_prefix = $value[1];
			$filter_title = $value[2];
			$filter_short_name = $value[3];
			try {
				$check_filter_list = $dbpdo->prepare('SELECT * FROM filter_list
				WHERE filter_list_prefix = :prefix');
				$check_filter_list->bindParam('prefix', $filter_prefix);
				$check_filter_list->execute();

				if($check_filter_list->rowCount()>0) {
					echo " ";
				} else {
					$data_arr = array(
						'filter_prefix_id' => $prefix_id,
						'filter_prefix' => $filter_prefix,
						'filter_title' => $filter_title,
						'filter_short_naem' => $filter_short_name
					);

					install_data( $table_name, $data_arr );
				} 

			} catch(PDOException $e) {
				echo " таблицы не существует 231321 </pre>";
			}
		}
	}
	//end filter
	
	//provider_name
	if($table_name == 'stock_provider') {
		foreach ($array as $data) {
			try {
				$check_provider = $dbpdo->prepare('SELECT * FROM `stock_provider`
				WHERE provider_name = :provider_name');
				$check_provider->bindParam('provider_name', $data);
				$check_provider->execute();

				if($check_provider->rowCount()>0) {
					echo " данные есть";
				} else {
					$data_arr = array(
						'provider_name' => $data
					);

					install_data( $table_name, $data_arr );
					echo 'stock_providerstock_providerstock_provider';
					$update_stock_provider = $dbpdo->prepare("UPDATE stock_list INNER JOIN stock_provider ON stock_list.stock_provider = stock_provider.provider_name SET stock_list.product_provider = stock_provider.provider_id WHERE stock_list.stock_type = 'phone' ");
					$update_stock_provider->execute();
			
					// $unset_stock_provider = $dbpdo->prepare("UPDATE stock_list SET stock_provider = NULL WHERE stock_type = 'phone' ");
					// $unset_stock_provider->execute();	
					// $unset_stock_provider = $dbpdo->prepare("UPDATE stock_list SET stock_provider = NULL WHERE stock_provider = :datas ");
					// $unset_stock_provider->bindValue('datas', $data);
					// $unset_stock_provider->execute();										
				} 

			} catch(PDOException $e) {
				echo $e."\n";
				echo " таблицы ".$table_name." не существует 11111 </pre>";
			}
		}
	}

	if($table_name == 'stock_category') {
		foreach ($array as $data) {
			try {
				$check_category = $dbpdo->prepare('SELECT * FROM `stock_category`
				WHERE category_name = :cat_name');
				$check_category->bindParam('cat_name', $data);
				$check_category->execute();

				if($check_category->rowCount()>0) {
					echo " ";
				} else {
					$data_arr = array(
						'category_name' => $data
					);

					echo 'stock_categorystock_categorystock_category';

					install_data( $table_name, $data_arr );

					$update_stock_category = $dbpdo->prepare("UPDATE stock_list INNER JOIN stock_category ON stock_list.stock_provider = stock_category.category_name SET stock_list.product_category = stock_category.category_id WHERE stock_list.stock_type = 'akss' ");
					$update_stock_category->execute();
			
					// $unset_stock_category = $dbpdo->prepare("UPDATE stock_list SET stock_provider = NULL WHERE stock_provider = :datas ");
					// $unset_stock_category->bindValue('datas', $data);
					// $unset_stock_category->execute();					
				} 

			} catch(PDOException $e) {
				echo $e."\n";
				echo " таблицы ".$table_name." не существует 11111 </pre>";
			}
		}
	}	
}


//заполняю таблицу данными
function install_data($table_name, $data_arr) {
	global $dbpdo;
	/******filter data******/
	if($table_name === 'filter') {
		$filter_type = $data_arr['filter_type'];
		$filter_value = $data_arr['filter_value'];

		$add_fiter = $dbpdo->prepare('INSERT INTO filter (filter_type, filter_value)  VALUES (?, ?) ');
		$add_fiter->execute([$filter_type, $filter_value]);
	}	
	/******filter data******/

	if($table_name == 'function_settting') {
		$sett_id = $data_arr['sett_id'];
		$sett_name = $data_arr['sett_name'];

		$add_sett = $dbpdo->prepare('INSERT INTO function_settting (sett_custom_id, sett_name) VALUES (?, ?)');
		$add_sett->execute([$sett_id, $sett_name]);
	}

	if($table_name == 'th_list') {
		$th_description = $data_arr['th_description'];
		$th_name = $data_arr['th_name'];

		$add_th = $dbpdo->prepare('INSERT INTO th_list (th_description, th_name) VALUES (?, ?)');
		$add_th->execute([$th_description, $th_name]);
	}	

	if($table_name == 'filter_list') {
		$prefix_id = $data_arr['filter_prefix_id'];
		$prefix = $data_arr['filter_prefix'];
		$title = $data_arr['filter_title'];
		$short_name  = $data_arr['filter_short_naem'];
		$add_filter_list = $dbpdo->prepare('INSERT INTO filter_list (filter_list_id, filter_list_prefix, filter_list_title, filter_short_name) VALUES (?, ?, ?, ?) ');
		$add_filter_list->execute([$prefix_id, $prefix, $title, $short_name]);
	}

	if($table_name == 'stock_provider') {
		$provider_name = $data_arr['provider_name'];

		$add_provider = $dbpdo->prepare('INSERT INTO stock_provider (provider_id, provider_name) VALUES (NULL, ?) ');
		$add_provider->execute([$provider_name]);		
	}

	if($table_name == 'stock_category') {
		$category_name = $data_arr['category_name'];

		$add_category = $dbpdo->prepare('INSERT INTO `stock_category` (category_id, category_name) VALUES (NULL, ?) ');
		$add_category->execute([$category_name]);		
	}	
}

//проверить на наличие столбца в таблице
function check_table_column_exists($table_name, $column_arr) {
	global $dbpdo;

	foreach ($column_arr as $row => $value) {
		$col_name = $value[0];
		$col_param = $value[1];

		try {
			//тут проверяем на наличие столбца
			$col_check = $dbpdo->prepare('SELECT '.$col_name.' FROM  '.$table_name.'  ');
			$col_check->execute();				
		} catch (Exception $e) {
			$data = array(
				'col_name' => $col_name,
				'col_param' => $col_param 
			);
			//если нет вызываем функци и добавляем
			alert_table_column($table_name, $data);
		}
		
	}


}

//добавить новый столбец к таблице 
function alert_table_column($table_name, $data) {
	global $dbpdo;
	$col_name = $data['col_name'];
	$col_param = $data['col_param'];

	$insert_col_sql = "ALTER TABLE $table_name  ADD $col_name $col_param ";
	$dbpdo->exec($insert_col_sql);
}

//обновляем таблицу
function update_column_data($table_name, $column_list, $data) {
	global $dbpdo;
	//проверяем на доступноть таблицы
	try {
		foreach ($column_list as $col_name) {
			$query = "SELECT $col_name FROM  $table_name";
			$sel = $dbpdo->prepare($query);
			$sel->execute();
			if($sel->rowCount()>0) {
				foreach ($data as $type => $value) {
					$upd_query = "UPDATE $table_name SET $col_name = ? WHERE $col_name = ?";
					$upd = $dbpdo->prepare($upd_query);
					$upd->execute([$value, $type]);
					
				}
			}

		}
	} catch (Exception $e) {
		echo "Error when update table";
	}

}




function drop_column($table_name, $column_param) {
//первым парамтером идет название таблицы
//дальше массив со столбцами 
global $dbpdo;
	try {
		foreach ($column_param as $col_name) {
			echo $col_name;
			$drop_sql = $dbpdo->prepare("ALTER TABLE $table_name DROP COLUMN $col_name");
			$drop_sql->execute();
		}
	} catch (Exception $e) {
		echo "Ошибка при удалении таблицы";
	}
}


function trun_cate($table_name) {
	global $dbpdo;
	try {
		$clean_db = $dbpdo->prepare("TRUNCATE TABLE $table_name");
		$clean_db->execute();
	} catch (Exception $e) {
		echo "error when truncate database !";
	}
}

//манипуляции с базой данных - дикий треш. сделал пока не додумаюсь 
function custom_sql($action) {
	global $dbpdo;

	if($action == 'stock_filter') {

	$filters_list = [];	
	$for_active_filters = [];
	$get_id = [];

	try {
		$get_active_filters = $dbpdo->prepare('SELECT stock_id, filter_color_id, filter_storage_id, filter_ram_id, filter_used_id FROM stock_filter');
		$get_active_filters->execute();
			if($get_active_filters->rowCount()>0) {
				while ($row = $get_active_filters->fetch())
					$for_active_filters[] = $row;
				foreach ($for_active_filters as $row) {

					$stock_id 	= $row['stock_id'];
					$color_id 	= $row['filter_color_id'];
					$storage_id = $row['filter_storage_id'];
					$ram_id 	= $row['filter_ram_id'];
					$used_id 	= $row['filter_used_id'];

					$filters_list[$stock_id] = [ 'id' => array(
						 $color_id, 
						 $storage_id,
						 $ram_id,
						 $used_id
					)];
				}
				//удаляем не нужные табицы
				drop_column('stock_filter', ['filter_color_id', 'filter_storage_id', 'filter_ram_id', 'filter_used_id']);
				//очистить бд
				trun_cate('stock_filter');

				foreach ($filters_list as $stock_id => $value) {
					foreach ($value['id'] as $filter_id ) {
						try {
							if($filter_id > 0) {
								//тут проверяем
								$check_filter = $dbpdo->prepare('SELECT * FROM stock_filter WHERE active_filter_id = :filter_id AND stock_id = :stock_id ');
								$check_filter->bindParam('stock_id', $stock_id);
								$check_filter->bindParam('filter_id', $filter_id);
								$check_filter->execute();
								if($check_filter->rowCount()>0) {
									echo "Запись с id ".$stock_id. ' и фильтром '. $filter_id . ' есть в базе <br>';
								} else {
									echo "asdsa";
									$insert_filter = $dbpdo->prepare('INSERT INTO stock_filter (stock_id, active_filter_id) VALUES (?, ?)');
									$insert_filter->execute([$stock_id, $filter_id]);
								}
							}
						} catch (Exception $e) {
							//тут добавляем в бд
							echo "Запись не обнаружена";
						}
					}
				}
			}		
		} catch (Exception $e) {
			echo "Ошибка";
		}
	}	


	//here next action if($action == 'some action') {...}
}
