<?php 

include 'function.php';
ls_var_dump(	
    [accounts_type] =>  {"account_type":"Marginal","spred":"From $0.02","withdraw":"Yes"},
		{"account_type":"Cash","spred":"From $0.02","withdraw":"Yes"}
	, 
    [min_depo_aside] => $1, 
    [leverage_aside] => 1:2 
?>


<div>

<?php 

$text = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}]/u', '', getUser('get_id') );
		echo getUser('get_id'); 


?>


</div>

<?php
exit;
$usr = '';
$usr = $usr->getUser('get_id');
echo $usr;
exit();
$type = 'akss';
$page = 'report';


ls_var_dump(filter_category(['color', 'used'], NULL));

exit();
$que = $dbpdo->prepare("SELECT
stock_id, COUNT(*)
FROM
stock_order_report
WHERE stock_type = 'phone'
AND stock_order_visible = 0
GROUP BY
stock_id
HAVING 
COUNT(*) > 1");

$que->execute();
while($row = $que->fetch()){
echo $row['stock_id'].'<br>';
}


exit();

$stock = render_data_template([
	'type' => $type,
	'page' => $page,
	'search' => [
		'param' => " AND stock_order_report.order_stock_id = :stock_id   ",
		'bindList' => array(
			'stock_id' => 1274
		)
	]
]);

ls_var_dump($stock);


 exit();




	$arr = [
		0 => [
			'stock_coint' => 'asd'
		],

		1 => [
			'stock_coint' => '23'
		],		
	];

	if(array_keys('stock_coint', $arr)) {
		echo 'asda';
	}

	ls_var_dump($arr);

exit();
get_stock_count_result(['type' => 'phone', 'page' => 'report']);

exit();

$param[] = array(
	'param' => '  WHERE stock_phone_imei = :stock_imei ',	
	'bindList' => array(
		'stock_imei' => '4939294'
	)
);
$stock_list = ls_db_request($param,
	[
		'table_name' => 'stock_list',
		'base_query' => 'SELECT * FROM stock_list',
		'sort_by'	 => ' ORDER BY stock_id DESC'
	]
);

if(!empty($stock_list)) {
	foreach($stock_list as $key => $value) {
		echo $value['stock_name'];
	}
}


exit();
if($_POST['asdasd']) {
	echo 'asdsa';
}

// ls_var_dump(get_last_added_stock([
// 	'type' => 'phone',
// 	'col_name' => array(
// 		'stock_id',
// 		'stock_name'
// 	)
// ]));


// $var = get_last_added_stock([
// 	'type' => 'phone',
// 	'col_name' => array(
// 		'stock_id',
// 		'stock_name'
// 	)
// ]);

// echo $var['stock_id'];

exit();
$arr = array(
	'hellow' => 'helo'
);


if(!array_key_exists('d', $arr)) {
	echo 'Ошибка';
} 

exit();
$sd =  ls_get_fixed_post($_POST['po']);
ls_var_dump(
	[
		'hellow' => 'world',
		'post'   => ls_get_fixed_post($_POST['po'])
	]
);


function ls_get_fixed_post($post) {
	if(!empty($post) && $post) {
		return $post;
	} else {
		return false;
	}
}

exit();

	$que = $dbpdo->prepare("SELECT
    stock_id, COUNT(*)
FROM
    stock_order_report
	WHERE stock_type = 'phone'
	AND stock_order_visible = 0
GROUP BY
    stock_id
HAVING 
    COUNT(*) > 1");

$que->execute();
while($row = $que->fetch()){
	echo $row['stock_id'].'<br>';
}

exit();
ls_include_tpl();

$i = 1;

if($i) {
	echo 'yest';
}

exit();

// echo get_filter_li_list(['color', 'ram', 'used']); 

 // echo "<pre>";
 // var_dump(filter_category(['color', 'storage']));
 // echo "</pre>";


 //echo "<pre>";
 // var_dump(filter_category(['color', 'storage'], '87'));
 // echo "</pre>";

// function custom_sql($action) {
// 	global $dbpdo;


// 	if($action == 'stock_filter') {
// 	$filters_list = [];	
// 	$for_active_filters = [];
// 	$get_id = [];

// 	try {
// 		$get_active_filters = $dbpdo->prepare('SELECT stock_id, filter_color_id, filter_storage_id, filter_ram_id, filter_used_id FROM stock_filter');
// 		$get_active_filters->execute();
// 			if($get_active_filters->rowCount()>0) {
// 				while ($row = $get_active_filters->fetch())
// 					$for_active_filters[] = $row;
// 				foreach ($for_active_filters as $row) {

// 					$stock_id 	= $row['stock_id'];
// 					$color_id 	= $row['filter_color_id'];
// 					$storage_id = $row['filter_storage_id'];
// 					$ram_id 	= $row['filter_ram_id'];
// 					$used_id 	= $row['filter_used_id'];

// 					$filters_list[$stock_id] = [ 'id' => array(
// 						 $color_id, 
// 						 $storage_id,
// 						 $ram_id,
// 						 $used_id
// 					)];
// 				}
// 				//удаляем не нужные табицы
// 				drop_column('stock_filter', ['filter_color_id', 'filter_storage_id', 'filter_ram_id', 'filter_used_id']);

// 				foreach ($filters_list as $stock_id => $value) {
// 					foreach ($value['id'] as $filter_id ) {
// 						try {
// 							if($filter_id > 0) {
// 								//тут проверяем
// 								$check_filter = $dbpdo->prepare('SELECT * FROM stock_filter WHERE active_filter_id = :filter_id AND stock_id = :stock_id ');
// 								$check_filter->bindParam('stock_id', $stock_id);
// 								$check_filter->bindParam('filter_id', $filter_id);
// 								$check_filter->execute();
// 								if($check_filter->rowCount()>0) {
// 									echo "Запись с id ".$stock_id. ' и фильтром '. $filter_id . ' есть в базе <br>';
// 								} else {
// 									echo "asdsa";
// 									$insert_filter = $dbpdo->prepare('INSERT INTO stock_filter (stock_id, active_filter_id) VALUES (?, ?)');
// 									$insert_filter->execute([$stock_id, $filter_id]);
// 								}
// 							}
// 						} catch (Exception $e) {
// 							//тут добавляем в бд
// 							echo "Запись не обнаружена";
// 						}
// 					}
// 				}
// 			}		
// 		} catch (Exception $e) {
// 			echo "Ошибка";
// 		}
// 	}		
// }







// function drop_column($table_name, $column_param) {
// //первым парамтером идет название таблицы
// //дальше массив со столбцами 
// global $dbpdo;
// 	try {
// 		foreach ($column_param as $col_name) {
// 			echo $col_name;
// 			$drop_sql = $dbpdo->prepare("ALTER TABLE $table_name DROP COLUMN $col_name");
// 			$drop_sql->execute();
// 		}
// 	} catch (Exception $e) {
// 		echo "Ошибка при удалении таблицы";
// 	}
// }


	// print_r(ls_var_dump($filters_list));


	// $table_name = 'customer';
	// $column_list = ['customer_name'];
	// $data = ['Emil' => 'Emil Upd'];

	// //проверяем на доступноть таблицы
	// try {
	// 	foreach ($column_list as $col_name) {
	// 		$query = "SELECT $col_name FROM  $table_name";
	// 		$sel = $dbpdo->prepare($query);
	// 		$sel->execute();
	// 		if($sel->rowCount()>0) {
	// 			foreach ($data as $type => $value) {
	// 				$upd_query = "UPDATE $table_name SET $col_name = ? WHERE $col_name = ?";
	// 				$upd = $dbpdo->prepare($upd_query);
	// 				$upd->execute([$value, $type]);
					
	// 			}
	// 		}

	// 	}
	// } catch (Exception $e) {
	// 	echo "Error when update table";
	// }


// echo 'есть: '.get_active_filters('color', '91').'<br>';

// echo " нет: ".get_active_filters('color', '88235');



// $arr['stock_id'] = [ 'stock_id' => '232', 'filter' => array(55, 66, 77, 43243)];

// print_r(ls_var_dump($arr));

// foreach ($arr as $row => $value) {
// 	$stock_id = $value['stock_id'];
// 	foreach ($value['filter'] as $key => $filter_id) {
// 		$filter_id;
// 	}
// }


$menu_query = [
	'terminal' =>	[
		'title' => 'Əməliyyatlar',
		'img_big' => '/img/icon/terminal.png',
		'img_small'	=> '/img/icon/sidebar/wb_fill/store.png',
		'link'  => '/page/terminal/terminal.php',
		'background-color' => 'rgba(0, 150, 136, 0.1)'
	],	
	'stock' =>	[
		'title' => 'Anbar',
		'img_big' => '/img/icon/stock.png',
		'img_small'	=> '/img/icon/sidebar/wb_fill/stock.png',
		'link'  => '/page/stock/stock.php',
		'background-color' => 'rgba(72, 61, 139, 0.1)'
	],	
	'report' => [
		'title' => 'Hesabat',
		'img_big' => '/img/icon/report.png',
		'img_small'	=> '/img/icon/sidebar/wb_fill/report.png',
		'link'  => '/page/report/report.php',
		'background-color' => 'rgba(33, 150, 243, 0.1)'
	],	
	'note' => [
		'title' => 'Notlar',
		'img_big' => '/img/icon/order.png',
		'img_small'	=> '/img/icon/sidebar/wb_fill/note.png',
		'link'  => '/page/note/note.php',
		'background-color' => 'rgba(255, 255, 101, 0.1)'
	],	
	'rasxod' => [
		'title' => 'Xərc (Rasxod)',
		'img_big' => '/img/icon/rasxod.png',
		'img_small'	=> '/img/icon/sidebar/wb_fill/rasxod.png',
		'link'  => '/page/rasxod/rasxod.php',
		'background-color' => 'rgba(255, 48, 48, 0.1)'
	],	
	'admin' => [
		'title' => 'Admin',
		'img_big' => '/img/icon/rasxod.png',
		'img_small'	=> '/img/icon/sidebar/wb_fill/rasxod.png',
		'link'  => '/page/admin/admin.php',
		'background-color' => 'rgba(255, 48, 48, 0.1)'
	]
	];


foreach($menu_query as $row => $value ) {
	echo $row;
}
// print_r(ls_var_dump($meny));/



?>

<div style="margin-top: 100px;">
	

</div>