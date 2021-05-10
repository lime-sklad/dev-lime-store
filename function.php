<?php
require __DIR__.'/db/config.php';
//upd 
require __DIR__.'/private.function.php';
require __DIR__.'/core/function/stock.function.php';
require __DIR__.'/core/action/admin/user.function.php';
require __DIR__.'/include/lib_include.php';

//проверка доступа страницы
access_request_action($_SERVER['REQUEST_URI']);
//проверка достпа запросов
access_request_uri($_SERVER['REQUEST_URI']);

$lincence_check = $dbpdo->prepare("SELECT * FROM licence");
$lincence_check->execute();

$lince_check_row = $lincence_check->fetch();

$lincence_status = $lince_check_row['licence_active'];

if($lincence_status == 0)
{
	header('Location: licence.php');
	exit();
}

if($lincence_status == 1)
{
	$licence_deactive_date = $lince_check_row['licence_active_deactive'];
}

$manat_image = '<img src="/img/icon/manat.png" class="manat_con_class">';
$manat_image_green = '<img src="/img/icon/manat_green.png" class="manat_con_class">';
//маркировака возврата
$stock_return_image = '<img src="img/icon/investment.png" style="width: 20px; height:20px;" title="Bu mall vazvrat olunub">';



$update_check_day = 'Wednesday'; 

$ordertoday = date("d.m.Y");
$order_myear = date("m.Y");
$weak_now = date("l/off"); //date("l");
$deactive_date = date('d.m.Y', strtotime('+30 day'));


function get_my_dateyear() {
	return date("m.Y");
}

function get_my_datetoday() {
	return  date("d.m.Y");
}
function check_connections($var) {
	$ch = curl_init('https://www.google.com/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$get_connectino = curl_exec($ch);

	$info = curl_getinfo($ch);
	curl_close($ch);


	if($info['http_code'] == '200') {
		return $var;
	} else {
		$do = 'nothing';
	}
}

function tab_page_header($dest) {
?>
	<div class="note_header">
		<span class="header_text"><?php echo $dest; ?></span>
	</div>	
<?php
}

function tab_page_second_header($dest) {
?>
	<div class="note_header">
		<span class="header_text second_header"><?php echo $dest; ?></span>
	</div>	
<?php
}

//блок для принта чека
function printModal() {
?>
	<div class="print_div">
		<div class="close_print_module">
			<a href="javascript:void(0)" class="hide_print_module"><img src="img/icon/cancel-black.png"></a>
		</div>
		<div class="print_content" id="print_content">
		</div>
	</div>
<?php
}

//preloader

function get_preloader() {
	$preloader = '<div class="preloader_wrapper modal flex-cntr"> <img src="/img/icon/preloader-blue.gif"> </div>';
	return $preloader;
}


function ls_trim($var) {
	$var = 	trim($var);
	$var =  htmlspecialchars($var);
	$var =  stripcslashes($var);
	$var =  strip_tags($var);
	return $var;	
}	


function get_table_header() {
	global $table_header;

	$table_header = array(
		'th_sale_serial'		=> '<th class="th_w60" title="Satış nomresi">№</th>',
		'th_day_sale'			=> '<th class="th_w120">Satış günü</th>',
		'th_prod_name'			=> '<th class="th_w250">Malın adı</th>',
		'th_imei'				=> '<th class="th_w250">IMEI</th>',
		'th_sale_price'			=> '<th class="th_w80">Qiymət</th>',
		'th_report_note'		=> '<th class="th_w120" title="kimə satılıb">Qeyd</th>',
		'th_count'				=> '<th class="th_w80">Sayı</th>',
		'th_profit'				=> '<th class="th_w80">Xeyir</th>',
		'th_provider'			=> '<th class="th_w200">Təchizatçı</th>',
		'th_category'			=> '<th class="th_w200">Kategoriya</th>',

		//stock
		// 'th_serial'				=> '<th class="th_w60">Seriya №</th>',
		'th_serial'				=> '<th class="th_w40">№</th>',
		'th_buy_day'			=> '<th class="th_w120">Alış günü</th>',
		'th_buy_price' 			=> '<th class="th_w80">Alış qiyməti</th>',

		'th_date'				=> '<th class="th_w120">Tarix</th>',
		'th_decsription'		=> '<th class="th_w250">Tasvir</th>',

		'th_note_cont'			=> '<th class="th_w250">Qeyd</th>',
		'th_rasxod_decsription' => '<th class="th_w_auto">Tasvir</th>',

		'th_total_rasxod'		=> '<th class="th_w120">Ümumi xərclər</th>',

	);

	return $table_header;
}

//подключаем совй кастомный шаблонизатор (жесть мне будет стыдно если кто то сюда заглянет)
function ls_include_tpl() {
	$dir = '/core/template/tpl_function.php';

	require_once $_SERVER['DOCUMENT_ROOT'].$dir;
}

function echo_err() {
	echo "error";
}

//меню на главной
// function getPageUrlMain() {
// 	global $get_view_stock; 		
// 	global $sell_stock;				
// 	global $report;					
// 	global $no_stock_order;			
// 	global $rasxod;					
// 	global $recycle;				

// 	$get_view_stock   =  'products/terminal/terminal.php';  		//страница продажы товаров         
// 	$sell_stock 	  =  'sell_stock.php';							//страница склада товаров
// 	$report 		  =  'report.php';								//страница отчета
// 	$no_stock_order   =  'no_stock_order.php';						//страница заказов/заметки
// 	$rasxod 		  =  'rasxod.php';								//страница расходов
// 	$recycle 		  =  'recycle';									//корзина


// 	return $get_view_stock; 
// 	return $sell_stock;		
// 	return $report;			
// 	return $no_stock_order;	
// 	return $rasxod;			
// 	return $recycle;

// }

function root_dir() {
	define('GET_ROOT_DIRS', $_SERVER['DOCUMENT_ROOT']);
	return true;
}




//ссылки на вкоадки на гавном меню
function get_tab_main_page() {
	global $menu_query; 
	$menu_query = [
		[
			'title' => 'Əməliyyatlar',
			'img_big' => '/img/icon/terminal.png',
			'img_small'	=> '/img/icon/sidebar/wb_fill/store.png',
			'link'  => '/page/terminal/terminal.php',
			'background-color' => 'rgba(0, 150, 136, 0.1)'
		],	
		[
			'title' => 'Anbar',
			'img_big' => '/img/icon/stock.png',
			'img_small'	=> '/img/icon/sidebar/wb_fill/stock.png',
			'link'  => '/page/stock/stock.php',
			'background-color' => 'rgba(72, 61, 139, 0.1)'
		],	
		[
			'title' => 'Hesabat',
			'img_big' => '/img/icon/report.png',
			'img_small'	=> '/img/icon/sidebar/wb_fill/report.png',
			'link'  => '/page/report/report.php',
			'background-color' => 'rgba(33, 150, 243, 0.1)'
		],	
		[
			'title' => 'Notlar',
			'img_big' => '/img/icon/order.png',
			'img_small'	=> '/img/icon/sidebar/wb_fill/note.png',
			'link'  => '/page/note/note.php',
			'background-color' => 'rgba(255, 255, 101, 0.1)'
		],	
		[
			'title' => 'Xərc (Rasxod)',
			'img_big' => '/img/icon/rasxod.png',
			'img_small'	=> '/img/icon/sidebar/wb_fill/rasxod.png',
			'link'  => '/page/rasxod/rasxod.php',
			'background-color' => 'rgba(255, 48, 48, 0.1)'
		],	
		[
			'title' => 'Admin',
			'img_big' => '/img/icon/rasxod.png',
			'img_small'	=> '/img/icon/sidebar/wb_fill/rasxod.png',
			'link'  => '/page/admin/admin.php',
			'background-color' => 'rgba(255, 48, 48, 0.1)'
		]

		// ,
		// [
		// 	'title' => 'Zibil qutusu',
		// 	'img_big' => '/img/icon/trash.png',
		// 	'img_small'	=> '/img/icon/sidebar/trash.png',
		// 	'link'  => 'recycle.php',
		// 	'background-color' => 'rgba(46, 26, 18, 0.1)'
		// ]		
		// [
		// 	'title' => 'Borclar',
		// 	'img_big' => '/img/icon/trash.png',
		// 	'img_small'	=> '/img/icon/sidebar/trash.png',
		// 	'link'  => '/core/pulgin/debt/debt.php',
		// 	'background-color' => 'rgba(255, 48, 48, 0.1)'
		// ]																			
	];
	return $menu_query;
}


//пути до фалов где что хрнаиться
function get_product_root_dir() {

	// global $tab_arr;
	// $tab_arr = array(
	// 	'get_category_root' =>  array(
	// 		'terminal' => '/page/terminal/terminal.php',
	// 		'stock'	   => '/page/stock/stock.php',
	// 		'icon' => '' 
	// 	),
	// 	'tab_terminal_phone' => array(
	// 		'title' => 'Telefonlar',
	// 		'tab_link' => '/page/terminal/terminal_phone.php',
	// 		'icon' => '' 
	// 	),
	// 	'tab_terminal_akss' => array(
	// 		'title' => 'Digər',
	// 		'tab_link' => '/page/terminal/terminal_akss.php',
	// 		'icon' => '' 
	// 	),
	// 	'tab_stock_phone' => array(
	// 		'title' => 'Telefonlar',
	// 		'tab_link' => '/page/stock/stock_phone.php',
	// 		'icon' => '' 
	// 	),
	// 	'tab_stock_akss' => array(
	// 		'title' => 'Digər',
	// 		'tab_link' => '/page/stock/stock_akss.php',
	// 		'icon' => '' 
	// 	),
	// 	'tab_report_phone' => array(
	// 		'title' => 'Telefonlar',
	// 		'tab_link' => '/page/report/report_phone.php',
	// 		'icon' => '' 
	// 	),
	// 	'tab_report_akss' => array(
	// 		'title' => 'Digər',
	// 		'tab_link' => '/page/report/report_akss.php',
	// 		'icon' => '' 
	// 	),
	// 	'tab_debt_history' => array(
	// 		'title' => 'История кредита',
	// 		'tab_link' => 'helloworld.php',
	// 		'icon' => '' 

	// 	),
	// 	'tab_debt_transaction' => array(
	// 		'title' => 'История Платижей',
	// 		'tab_link' => 'helloworld2.php',
	// 		'icon' => '' 

	// 	),
	// 	'tab_note_order' => array(
	// 		'title' => 'SIFARIŞLƏR',
	// 		'tab_link' => '/page/note/note_order.php',
	// 		'icon' => ''  
	// 	),
	// 	'tab_note_reminder' => array(
	// 		'title' => 'Xatırlatma',
	// 		'tab_link' => '/page/note/reminder.php',
	// 		'icon' => ''  
	// 	),
	// 	'tab_rasxod' => array(
	// 		'title' => 'Xərc',
	// 		'tab_link' => '/page/rasxod/rasxod_list.php',
	// 		'icon' => ''  
	// 	),
	// 	'tab_admin_user' => array(
	// 		'title' => 'İstifadəçilər',
	// 		'tab_link' => '/page/admin/user_list.php',
	// 		'icon' => '<img src="/img/icon/advanced.png" class="tab_icon">' 
	// 	)	
		 
	// );

}


//getCurrentTab old name
//функция которая выводит кнопки для переключения вкладок 
// data-tab-open="" - содержит класс вкладки, которы йнужно открыть 
function get_current_tab($conf) { ?>
<div class="tab_option <?php echo $conf['parent_modify_class']; ?>" >
	<div class="tab_change_box">
<?php 
	$tab_list = $conf['link_list'];
	$tab_arr = $conf['registry_tab_link'];
	$tab_active = $conf['default_link'];
	$modify_class = $conf['modify_class'];
	foreach ($tab_list as $tab_row) {
			$row = $tab_arr[$tab_row];
			$tab_title = $row['title'];
			$tab_link = $row['tab_link'];
			$icon_img = $row['icon'];

			if($row == $tab_arr[$tab_active]) {
				$active_tab = 'tab_activ';
			} else {
				$active_tab = '';
			}

			// get_tab_list_tpl($tab_title, $tab_link, $active_tab); old
			get_tab_list_tpl($arr = array(
				'title' => $tab_title,
				'link' => $tab_link,
				'active_link' => $active_tab,
				'modify_class' => $modify_class,
				'icon_img' => $icon_img

			));
		} ?>
		<div class="tab_selected_bcg"></div>
	</div>
</div>
	
<?php return true; }




//получем категорию товара
function get_product_category() {
	global $product_phone, 	
		   $product_akss,	
		   $product_service,
		   $note_category,
		   $rasxod_category;
	
	//телефоны
	$product_phone 		= 	'phone';

	//акссесуары
	$product_akss  		= 	'akss';            

	//услуги 
	$product_service 	= 	'service';

	//note
	$note_category 	 	= 	'order_note';

	//расходы
	$rasxod_category 	= 	'rasxod_cat';

}


//тип таблицы (terminal, stock, report и тд)
function get_table_svervice_type() {
	global $terminal,		 
		   $stock,
		   $report,
		   $note,
		   $rasxod;

	//таблица терминала(продажи товаров)
	$terminal = 'terminal';
	//табдлица склада товаров где они храняться 	
	$stock    = 'stock';
	//таблица отчета		
	$report   = 'report';
	//note
	$note     = 'note';		
	//reminder
	$rasxod   = 'rasxod';

	return $terminal; 
	return $stock;
	return $report;
	return $note;
	return $rasxod;
}



//шаблон поиска
//product_type - terminal, stock, report
//product_cat -  product_phone/akss
function search_input($arr) {

	$parent_class = $arr['parent_class'];
	$product_type = $arr['product_type'];
	$product_category = $arr['product_category'];
	$product_class =$arr['product_class'];
	$auto_complete = $arr['auto_complete'];
	$label = $arr['label'];
	$label_content = $arr['label_title'];
	$clear_button = $arr['clear_button'];
	
?>
	<div class="view_stock_search flex-cntr <?php echo $parent_class; ?>">
		<?php if($label === 'show') { ?>
			<span class="auto-cmplt-label"> <?php echo $label_content; ?> 
		<?php } ?></span>				
		<div class="input_wrapper">
			<img src="/img/icon/search-loupe.png" class="input-icon-plcholder">
			<input type="search" placeholder="Axtar" autocomplete="off" 
			id="get_item_stock_action" 
			data-stock-page="<?php echo  $product_type; ?>" 
			class="search_stock_input_action search_input <?php echo $product_class; ?>" 
			data-stock-type="<?php echo $product_category; ?>" 
			data-sort="search">
		<?php if( $auto_complete  == 'show') { auto_complete_select_wrapper_tpl(); } ?>
		</div>
		<?php if ($clear_button == 'show') { ?>
		<div class="reset_akss_search_b">
			<a href="javascript:void(0)" class="reset_stock_view_search_action reset_akss_search_style btn">Geri</a>
		</div>
		<?php } ?>
	</div>

<?php	
}
 

//шаблон таблицы для терминала телефонов
function get_terminal_phone_table_row($get_product_table) {
	$stock_id 				= $get_product_table['stock_id'];			
	$stock_name 			= $get_product_table['stock_name'];		
	$stock_imei 			= $get_product_table['stock_phone_imei'];		
	$stock_first_price 		= $get_product_table['stock_first_price'];	
	$stock_second_price		= $get_product_table['stock_second_price'];
	$stock_return_status	= $get_product_table['stock_return_status'];
	$stock_provider			= $get_product_table['stock_provider'];	
	$manat_image			= $get_product_table['manat_image'];
	$stock_return_image 	= $get_product_table['stock_return_image'];
	$user_role = getUser('get_user_role');
?>
	<tr class="stock-list" id="<?php echo $stock_id; ?>">

	  <td class="table_stock table_stock_id_box">
	  	<a href="javascript:void(0)" class="stock_info_link_block "> 
	  		<span class="stock_info_text"> <?php echo $stock_id ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text"> <?php echo trim($stock_name); ?> </span> 
	  	</a>
	  </td>

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text"> <?php echo $stock_imei ?> </sapn>
	  	</a>
	  </td>	
	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block "> 
	  		<span class="stock_info_text"> <?php echo $stock_first_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>
	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text"> <?php echo $stock_second_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text"> <?php echo trim($stock_provider); ?> </span> 
	  	</a>
	  </td>

	  <td class="ls-text-center">	
		<?php
		//значение возврата ->  0 - дефолт, 1 - возврат
		$v = $stock_return_status;
		echo ($v == 1) ? $stock_return_image : ' ';		
		?>
	  </td>	

	</tr>

<?php 
}



//шаблон таблицы для терминала акссесуаров
function get_terminal_akss_table_row($get_product_table) {
	$stock_id 				= $get_product_table['stock_id'];			
	$stock_name 			= $get_product_table['stock_name'];				
	$stock_first_price 		= $get_product_table['stock_first_price'];	
	$stock_second_price		= $get_product_table['stock_second_price'];
	$stock_count			= $get_product_table['stock_count'];
	$stock_provider			= $get_product_table['stock_provider'];	
	$manat_image			= $get_product_table['manat_image'];
	$user_role = getUser('get_user_role');
?>

	<tr class="stock-list" id="<?php echo $stock_id; ?>">

	  <td class="table_stock table_stock_id_box">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_id ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text"> <?php echo trim($stock_name); ?> </span> 
	  	</a>
	  </td>	
	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_first_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>
	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_second_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text ter_stock_count"> <?php echo $stock_count; ?></span>
	  		<span class="mark mark--count"> ədəd</span>
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text"> <?php echo trim($stock_provider); ?> </span> 
	  	</a>
	  </td>	 

	</tr>

<?php 
}


//шаблон таблицы для СКЛАДА телефонов
function get_stock_phone_table_row($get_product_table ) {

	$stock_id 				= $get_product_table['stock_id'];
	$stock_date             = $get_product_table['stock_date'];		
	$stock_name 			= $get_product_table['stock_name'];
	$stock_imei             = $get_product_table['stock_imei'];				
	$stock_first_price 		= $get_product_table['stock_first_price'];	
	$stock_second_price		= $get_product_table['stock_second_price'];
	$stock_provider			= $get_product_table['stock_provider'];	
	$manat_image			= $get_product_table['manat_image'];
	$return_image           = $get_product_table['stock_return_image'];
	if(isset($get_product_table['modify_class'])) { $modify_class  =  $get_product_table['modify_class']; } else { $modify_class = ''; }
	//дата $new_stock_date
 ob_start();
?>
	<tr class="stock-list <?php echo $modify_class ?>" id="<?php echo $stock_id; ?>">

	  <td class="table_stock table_stock_id_box">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_id ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text"> <?php echo trim($stock_date); ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text s_result_name"> <?php echo trim($stock_name); ?> </span> 
	  	</a>
	  </td>	

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text s_result_imei"> <?php echo $stock_imei; ?> </sapn>
	  	</a>
	  </td>	

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text s_result_fprice"> <?php echo $stock_first_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text s_result_sprice"> <?php echo $stock_second_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text s_result_provider"> <?php echo trim($stock_provider); ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<?php echo $return_image; ?>
	  </td>

	</tr>
<?php
    $result = ob_get_clean();
 
    return $result;
}





//шаблон таблицы для СКЛАДА акссесуаров
function get_stock_akss_table_row($get_product_table ) {

	$stock_id 				= $get_product_table['stock_id'];
	$stock_date             = $get_product_table['stock_date'];		
	$stock_name 			= $get_product_table['stock_name'];			
	$stock_count 			= $get_product_table['stock_count'];			
	$stock_first_price 		= $get_product_table['stock_first_price'];	
	$stock_second_price		= $get_product_table['stock_second_price'];
	$stock_provider			= $get_product_table['stock_provider'];	
	$manat_image			= $get_product_table['manat_image'];
	$red 					= '';

	if($stock_count <= 0) {
		$red = 'red';
	}

	//дата $new_stock_date
 ob_start();
?>
	<tr class="stock-list" id="<?php echo $stock_id; ?>">

	  <td class="table_stock table_stock_id_box">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_id ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text"> <?php echo trim($stock_date); ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text s_result_name"> <?php echo trim($stock_name); ?> </span> 
	  	</a>
	  </td>	

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text s_result_fprice"> <?php echo $stock_first_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text s_result_sprice"> <?php echo $stock_second_price; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td class="table_stock <?php echo $red; ?>">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text s_result_count"> <?php echo $stock_count; ?> </span>
	  		<span class="mark mark--count"> ədəd</span>
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action">
	  		<span class="stock_info_text s_result_provider"> <?php echo trim($stock_provider); ?> </span> 
	  	</a>
	  </td>

	</tr>
<?php
    $result = ob_get_clean();
 
    return $result;
}





//шаблон таблицы для ОТЧЕТА телефонов
function get_report_phone_tamplate($get_product_table) {
	$stock_id 			= $get_product_table['stock_id'];			
	$order_date 		= $get_product_table['order_date'];		
	$stock_name 		= $get_product_table['stock_name'];		
	$stock_imei 		= $get_product_table['stock_imei'];	
	$stock_sprice 		= $get_product_table['stock_sprice'];
	$stock_provider 	= $get_product_table['stock_provider'];
	$stock_count 		= $get_product_table['stock_count'];	
	$order_who_buy 		= $get_product_table['order_who_buy'];
	$stock_profit 		= $get_product_table['stock_profit'];
	$manat_image 		= $get_product_table['manat_image'];
?>
	<tr class="stock-list" id="<?php echo $stock_id; ?>">

	  <td class="table_stock table_stock_id_box">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_id ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action" data-sort="date">
	  		<span class="stock_info_text"> <?php echo trim($order_date); ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action" data-sort="name">
	  		<span class="stock_info_text"> <?php echo trim($stock_name); ?> </span> 
	  	</a>
	  </td>

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text"> <?php echo $stock_imei ?> </sapn>
	  	</a>
	  </td>	

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_sprice; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action" data-sort="provider">
	  		<span class="stock_info_text"> <?php echo trim($stock_provider); ?> </span> 
	  	</a>
	  </td>


	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block" title="<?php echo $order_who_buy; ?>"> 
	  		<span class="stock_info_text"> <?php echo $order_who_buy ?> </sapn>
	  	</a>
	  </td>	

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_count ?> </sapn>
	  	</a>
	  </td>	

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"><?php echo $stock_profit ?> </sapn>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>	

	</tr>

<?php 
}



//шаблон таблицы для ОТЧЕТА акссесуаров
function get_report_akks_tamplate($get_product_table) {
	$stock_id 			= $get_product_table['stock_id'];		
	$order_date 		= $get_product_table['order_date'];		
	$stock_name 		= $get_product_table['stock_name'];	
	$stock_sprice 		= $get_product_table['stock_sprice'];
	$stock_provider 	= $get_product_table['stock_provider'];
	$stock_count 		= $get_product_table['stock_count'];	
	$order_who_buy 		= $get_product_table['order_who_buy'];
	$stock_profit 		= $get_product_table['stock_profit'];
	$manat_image 		= $get_product_table['manat_image'];
?>
	<tr class="stock-list" id="<?php echo $stock_id; ?>">

	  <td class="table_stock table_stock_id_box">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_id ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action" data-sort="date">
	  		<span class="stock_info_text"> <?php echo trim($order_date); ?> </span> 
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action" data-sort="name">
	  		<span class="stock_info_text"> <?php echo trim($stock_name); ?> </span> 
	  	</a>
	  </td>

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_sprice; ?> </span>
	  		<span class="mark"> <?php echo $manat_image; ?> </span>
	  	</a>
	  </td>

	  <td>
	  	<a href="javascript:void(0)" class="stock_name_box_link_btn get_item_stock_action" data-sort="provider">
	  		<span class="stock_info_text"> <?php echo trim($stock_provider); ?> </span> 
	  	</a>
	  </td>


	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block" title="<?php echo $order_who_buy; ?>"> 
	  		<span class="stock_info_text"> <?php echo $order_who_buy ?> </sapn>
	  	</a>
	  </td>

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"> <?php echo $stock_count ?> </sapn>
	  	</a>
	  </td>	

	  <td class="table_stock">
	  	<a href="javascript:void(0)" class="stock_info_link_block flex-cntr"> 
	  		<span class="stock_info_text"><?php echo $stock_profit ?> </sapn>
	  	</a>
	  </td>	

	</tr>


<?php
}




// //дефолтная вкладка  терминал 
// function get_default_terminal_tab() {
// 	// $default_tab = 'terminal_phone.php';
// 	// define('get_default_terminal_tab',$default_tab);
// 	require_once '/terminal_phone';
// }

// //дефолтная вкладка  склада 
// function get_default_stock_tab() {
// 	$default_tab = 'stock_phone.php';
// 	define('get_default_stock_tab',$default_tab);
// }


//модальное окно успешно выполнено функция
function success_done() { ?> 
	<div class="success_notify"></div>
<?php
}

//модальное коно ошибка выполнено функция
function fail_notify() { ?>
	<div class="fail_notify red"></div>
<?php
}

//шаблон оформления заказа - телефон
function order_terminal_template_phone( $give_product_id,
								  		$o_product_name,
								  		$o_product_imei ) {
?>


<div class="module_order_wrp ls-custom-scrollbar">

	<ul class="modal_order_form" data-order-id="<?php echo $give_product_id; ?>">
		<li class="order_modal_list">
			<span class="module_order_desrioption">Malın adı: </span>
			<span class="modal_stock_info module_stock_span_name"><?php echo $o_product_name; ?></span>
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">IMEI: </span>
			<span class="modal_stock_info module_stock_span_imei"><?php echo $o_product_imei; ?></span>
		</li>

		<?php order_filter_list_tpl('terminal', 'phone', $give_product_id); ?>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Qeyd: </span>
			<input type="text" class="module_stock_input_whobuy order_input order_note_action" placeholder="Ad...">
		</li>	

		<li class="order_modal_list">
			<span class="module_order_desrioption">Malın qiyməti: </span>
			<input type="num" autocomplete="off" class="order_price_stock order_input order_price_action" placeholder="0">
			<input type="hidden"  class="order_count_action" value="1">	
		</li>
		<li class="order_modal_list">
			<div class="order_total_wrp">
				<a href="javascript:void(0)" class="order_total_btn_style btn get_order_action">Satış</a>
			</div>
		</li>
	</ul>
	<div class="order_resault"></div>
</div>
<?php	
}


//шаблон оформления заказа - акссесуар
function order_terminal_template_akss( $give_product_id,
								  	   $o_product_name ) {

?>

<div class="module_order_wrp ls-custom-scrollbar">

	<ul class="modal_order_form" data-order-id="<?php echo $give_product_id; ?>">
		<li class="order_modal_list">
			<span class="module_order_desrioption">Malın adı: </span>
			<span class="module_stock_span_name"><?php echo $o_product_name; ?></span>
		</li>

		<?php order_filter_list_tpl('terminal', 'akss', $give_product_id); ?>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Qeyd: </span>
			<input type="text" class="module_stock_input_whobuy order_input order_note_action" placeholder="Ad...">
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Malin sayi: </span>
			<input type="text" autocomplete="off" class="order_input order_stock_count_style order_count_action" value="1">	
		</li>	

		<li class="order_modal_list">
			<span class="module_order_desrioption">Malin qiymeti: </span>
			<input type="num" autocomplete="off" class="order_input order_price_stock order_price_action" >		
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Cemi:</span>
			<span class="show_total_sum_order_action order_input order_total_sum_style">0</span>
			<input type="hidden"class="total_sum_order_stock">			
		</li>	

		<li class="order_modal_list">
			<div class="order_total_wrp">
				<a href="javascript:void(0)" class="order_total_btn_style btn get_order_action">Satış</a>
			</div>
		</li>

	</ul>

	<div class="order_resault"></div>
</div>


<?php
}



//шаблон редактирования продукта -  телефон sklad
function update_stock_phone_tamplate($get_prod_upd) {


$edit_stock_id 				= $get_prod_upd['edit_stock_id'];	
$edit_stock_name 			= $get_prod_upd['edit_stock_name'];		
$edit_stock_imei 			= $get_prod_upd['edit_stock_imei'];		
$edit_stock_provider 		= $get_prod_upd['edit_stock_provider'];			
$manat_image 				= $get_prod_upd['manat_image'];		
$edit_stock_fprice 			= $get_prod_upd['edit_stock_fprice'];			
$edit_stock_sprice 			= $get_prod_upd['edit_stock_sprice'];			
$edit_stock_count 			= $get_prod_upd['edit_stock_count'];
?>


<div class="delete_stock_module">
	<div class="receipet_success">
		<a><img src="img/icon/print-success.png"></a>
	</div>
	<div class="delete_stock_form">
		<span>Silmək istədiyinizə əminsiniz ?</span>
		<div class="module_delete_btn_link">
			<a href="javascript:void(0)" class="module_delete_btn" id="<?php echo $edit_stock_id; ?>"> Sil</a>
			<a href="javascript:void(0)" class="module_delete_btn_cancle"> Ləğv et</a>
		</div>
	</div>
</div>

<div class="module_order_wrp ls-custom-scrollbar">
	<ul class="modal_order_form" data-order-id="<?php echo $edit_stock_id; ?>">
		<li class="order_modal_list">
			<span class="module_order_desrioption">Ad: </span>
			<input type="text" class="edit_stock_input edit_sotck_name_input" value="<?php echo $edit_stock_name; ?>" >
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">IMEI </span>
			<input type="text" class="edit_stock_input edit_stock_imei_input" value="<?php echo $edit_stock_imei; ?>" >
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">Təchizatçı</span>
			<input type="text" class="edit_stock_input edit_stock_provider_input" value="<?php echo $edit_stock_provider; ?>" >
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">Alış qiyməti(<?php echo $manat_image; ?> ): </span>
			<input type="text" class="order_input order_price_action edit_stock_input edit_sotck_fprice_input" value="<?php echo $edit_stock_fprice; ?>" >
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">Satış qiyməti(<?php echo $manat_image; ?> ): </span>
			<input type="text" class="edit_stock_input edit_stock_sprice_input" value="<?php echo $edit_stock_sprice; ?>" >
		</li>

		<?php order_filter_list_tpl('stock', 'phone', $edit_stock_id) ?>

		<input type="hidden" class="upd_product_count" value="<?php echo $edit_stock_count; ?>">

		<li class="edit_btn_wrp">
			<a href="javascript:void(0)" class="delete_btn_link btn">Sil</a>
			<a href="javascript:void(0)" class="edit_upd_btn_link btn edit_stock_action">Saxla</a>
		</li>	
	</ul>

</div>	


<?php		
}



//шаблон редактирования продукта -  акссесуаров sklad
function update_stock_akss_tamplate($get_prod_upd) {

$edit_stock_id 				= $get_prod_upd['edit_stock_id'];	
$edit_stock_name 			= $get_prod_upd['edit_stock_name'];		
$edit_stock_provider 		= $get_prod_upd['edit_stock_provider'];			
$manat_image 				= $get_prod_upd['manat_image'];		
$edit_stock_fprice 			= $get_prod_upd['edit_stock_fprice'];			
$edit_stock_sprice 			= $get_prod_upd['edit_stock_sprice'];			
$edit_stock_count 			= $get_prod_upd['edit_stock_count'];			

?>

<div class="delete_stock_module">
	<div class="receipet_success">
		<a><img src="img/icon/print-success.png"></a>
	</div>
	<div class="delete_stock_form">
		<span>Silmək istədiyinizə əminsiniz ?</span>
		<div class="module_delete_btn_link">
			<a href="javascript:void(0)" class="module_delete_btn" id="<?php echo $edit_stock_id; ?>"> Sil</a>
			<a href="javascript:void(0)" class="module_delete_btn_cancle"> Ləğv et</a>
		</div>
	</div>
</div>

<div class="module_order_wrp ls-custom-scrollbar">
	<ul class="modal_order_form" data-order-id="<?php echo $edit_stock_id; ?>">
		<li class="order_modal_list">
			<span class="module_order_desrioption">Ad: </span>
			<input type="text" class="edit_stock_input edit_sotck_name_input" value="<?php echo $edit_stock_name; ?>" >
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">Kategoriya: </span>
			<input type="text" class="edit_stock_input edit_stock_provider_input" value="<?php echo $edit_stock_provider; ?>" >
		</li>
		<?php  echo order_filter_list_tpl('stock', 'akss', $edit_stock_id); ?>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Alış qiyməti(<?php echo $manat_image; ?> ): </span>
			<input type="text" class="order_input order_price_action edit_stock_input edit_sotck_fprice_input" value="<?php echo $edit_stock_fprice; ?>" >
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">Satış qiyməti(<?php echo $manat_image; ?> ): </span>
			<input type="text" class="edit_stock_input edit_stock_sprice_input" value="<?php echo $edit_stock_sprice; ?>" >
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Say: </span>
			<input type="text" class="edit_stock_input edit_stock_count_style upd_product_count" value="<?php echo $edit_stock_count; ?>">
		</li>

		<li class="order_modal_list edit_stock_count_add" style="opacity: 0.5">
			<a href="javascript:void(0)" title="Əlavə etmək" class="edit_custom_count edit_stock_add_count_ebable">
				<img src="img/icon/plus.png">
			</a>
			<span class="module_order_desrioption">Əlavə etmək(ədəd): </span>
			<span class="stock_add_mark_plus">+</span>
			<input type="text" disabled="" class="edited_custom_stock_count edit_stock_input edit_count_plus" placeholder="0">
		</li>	

		<div class="order_modal_list edit_stock_count_minus" style="opacity: 0.5">
			<a href="javascript:void(0)" title="Azaltmaq" class="edit_custom_count edit_stock_remove_count_ebable">
				<img src="img/icon/plus.png">
			</a>		
			<span class="module_order_desrioption">Azaltmaq</span>				
			<span class="minus_stock_count_mark">-</span>
			<input type="text" disabled="" class="edited_custom_stock_count edit_stock_input edit_count_minus">
		</div>

		<li class="edit_btn_wrp">
			<a href="javascript:void(0)" class="delete_btn_link btn">Sil</a>
			<a href="javascript:void(0)" class="edit_upd_btn_link btn edit_stock_action">Saxla</a>
		</li>	

	</ul>

</div>	


<?php		
}




//если товар есть в базе
function add_product_available($product_id) {
	global $add_stock_available;
$add_stock_available = '
<div class="add_stock_module_error">
	<div class="close_modal_btn_error close_error_module_action"><img src="img/icon/cancel-white.png"></div>
	<div class="module moudle_erro_imei_availible">
		<div class="module_cwrp">
			<div class="module_hdr">Bu product movcudur</div>
			<div class="module_err_option">
				<div  class="hide_stock_list stock-list" id="'.$product_id.'">
					<a href="javascript:void(0)" class="table_stock error_imei_btn close_error_module_action">Redaktə edin <span><img src="img/icon/edit-rasxod.png"></span>
					</a>
				</div>	
			</div>
		</div>
	</div>
</div>';

return 	$add_stock_available;
}


//сортировка даты
function echo_option_r($data_value) {
	echo '<option value="'.$data_value.'">'.$data_value.'</option>';
}

//rpoert - фильт по дате
function filt_report_date($type, $dbpdo, $currenDate, $sort_from) {
?>
<div class="report_date_select_wrp">
	<div class="report_date_select_box">
		<div class="report_date_fotm">
			<label for="report_options_list">Tarix: </label>
			<select class="report_options_list select_option_data" id="report_options_list" data-sort="full_date">
				<option value="<?= $currenDate; ?>"><?= $currenDate; ?></option>
			<?php
			if($sort_from == 'report') {
				$report_order_select = [];
				$report_select_opt = $dbpdo->prepare("SELECT DISTINCT order_my_date
				 FROM stock_order_report 
				 WHERE stock_type =:stock_type
				 AND stock_order_visible = 0 
				 AND order_stock_count > 0
				 GROUP BY order_stock_id DESC
				 ORDER BY order_stock_id DESC");
				$report_select_opt->bindParam('stock_type', $type);
				$report_select_opt->execute();
				

				while($report_select_row = $report_select_opt->fetch(PDO::FETCH_BOTH))
					$report_order_select[] = $report_select_row;

				foreach ($report_order_select as $report_select_row)
				{
					$data_value = $report_select_row['order_my_date'];
					echo_option_r($data_value);
				}
			} 


			if($sort_from == 'note') {
				$report_order_select = [];
				$report_select_opt = $dbpdo->prepare("SELECT DISTINCT order_stock_date
				 FROM no_availible_order 
				 WHERE note_type = :stock_type
				 AND order_stock_visible = 0
				 GROUP BY order_stock_id DESC
				 ORDER BY order_stock_id DESC");
				$report_select_opt->bindParam('stock_type', $type);
				$report_select_opt->execute();
				
				while($report_select_row = $report_select_opt->fetch(PDO::FETCH_BOTH))
					$report_order_select[] = $report_select_row;

				foreach ($report_order_select as $report_select_row)
				{
					$report_date = $report_select_row['order_stock_date'];
					echo_option_r($report_date);			
				}

			}

			if($sort_from == 'rasxod') {
				$report_order_select = [];
				$report_select_opt = $dbpdo->prepare("SELECT DISTINCT rasxod_year_date
				 FROM rasxod 
				 WHERE rasxod_visible = 0
				 GROUP BY rasxod_id DESC
				 ORDER BY rasxod_id DESC");
				$report_select_opt->execute();
				

				while($report_select_row = $report_select_opt->fetch(PDO::FETCH_BOTH))
					$report_order_select[] = $report_select_row;

				foreach ($report_order_select as $report_select_row)
				{
					$rasxod_arr = $report_select_row['rasxod_year_date'];
					echo_option_r($rasxod_arr);
				}			
			}

			?>
			</select>				
		</div>
	</div>
</div>
<?php
}
//выручка
function get_total_all_profit_phone($dbpdo, $order_myear, $product_category, $manat_image_green) {
		$order_myear = trim($order_myear);
		$product_category = trim($product_category);

		$arr_price = [];
		$arr_count = [];
		$getOptionNameList = [];

		//делае выборку и получем вырычку
		$check_total_price = $dbpdo->prepare("SELECT *
			FROM stock_order_report,stock_list
			WHERE stock_order_report.order_my_date = :mydateyear
			AND stock_order_report.stock_type = :product_cat
			AND stock_order_report.stock_order_visible = 0
			AND stock_order_report.order_stock_count > 0

			OR  stock_order_report.order_date = :mydateyears
			AND stock_order_report.stock_type = :product_cats
			AND stock_order_report.stock_order_visible = 0
			AND stock_order_report.order_stock_count > 0

			OR  stock_order_report.order_stock_name = :filtr_name1
			AND stock_order_report.order_my_date = stock_order_report.order_my_date
			AND stock_order_report.stock_type = :product_cats1
			AND stock_order_report.stock_order_visible = 0
			AND stock_order_report.order_stock_count > 0

			OR stock_list.stock_provider
			LIKE :filtr_name2  
			AND stock_order_report.stock_id = stock_list.stock_id
			AND stock_order_report.stock_order_visible = 0
			AND stock_order_report.stock_type = :product_cats3	
			AND stock_order_report.order_stock_count > 0

			GROUP BY stock_order_report.order_stock_id DESC
			ORDER BY stock_order_report.order_stock_id DESC
			");
		$check_total_price->bindParam('mydateyear', $order_myear, PDO::PARAM_INT);
		$check_total_price->bindParam('product_cat', $product_category, PDO::PARAM_INT);
		$check_total_price->bindParam('mydateyears', $order_myear, PDO::PARAM_INT);
		$check_total_price->bindParam('product_cats', $product_category, PDO::PARAM_INT);
		$check_total_price->bindParam('product_cats1', $product_category, PDO::PARAM_INT);			
		$check_total_price->bindParam('product_cats3', $product_category, PDO::PARAM_INT);			
		$check_total_price->bindValue('filtr_name1', $order_myear);
		$check_total_price->bindValue('filtr_name2', "%{$order_myear}%");
		$check_total_price->execute();
		// $check_total_price_row = $check_total_price->fetch();
		//выручка на за месяц
		// $total_price_money =  round($check_total_price_row['total_money'], 2);
		// var_dump($total_price_money, $order_myear);
		
		while($check_total_price_row = $check_total_price->fetch(PDO::FETCH_BOTH))
			$getOptionNameList[] = $check_total_price_row;
		foreach ($getOptionNameList as $check_total_price_row) {
			//получем выручку товара
			$order_total_profit = $check_total_price_row['order_total_profit'];
			//количество товара
			$order_total_count  = $check_total_price_row['order_stock_count'];

			$order_total_not_profit_price = $check_total_price_row['order_stock_total_price'];
				

			//добавляем в масив и потом сичитаем общую сумму
			$arr_total_price[] = $order_total_profit;
			//добавляем в массив количестов товара
			$arr_total_count[] = $order_total_count;
			//о
			$arr_total_not_profit[] = $order_total_not_profit_price;
		}
		//получаем общую сумму выручки
		$total_price_money = array_sum($arr_total_price);
		//общее количество товра
		$total_stock_count = array_sum($arr_total_count);
		//сумма товара 
		$total_not_profit = array_sum($arr_total_not_profit);

		//получаем сумму расхода ха текущий меясц
		$check_total_rasxod = $dbpdo->prepare("SELECT sum(rasxod_money) 
			as total_rasxod 
			FROM rasxod 
			WHERE rasxod_year_date = :mydateyear
			AND rasxod_visible = 0
			
			OR rasxod_day_date = :mydateyear2
			AND rasxod_visible = 0");
		$check_total_rasxod->bindParam('mydateyear', $order_myear, PDO::PARAM_INT);
		$check_total_rasxod->bindParam('mydateyear2', $order_myear, PDO::PARAM_INT);
		$check_total_rasxod->execute();
		$check_total_rasxod_row = $check_total_rasxod->fetch();
		//расход за месяц
		$total_rasxod_value = round($check_total_rasxod_row['total_rasxod'], 2);
		//если категория товара телфон тогда учитываем расходи а ессли ассесуар то - нет
		if($product_category == 'phone') {
			//из выручки вычитаем сумму расхода
			$final_total_price = $total_price_money - $total_rasxod_value;
		}
		if($product_category == 'akss') {
			$final_total_price = $total_price_money;
		}
		//выводить результат
if(is_data_access_available('th_count')): ?>
<tr class="total_value_table">
	<th class="total_value_th" style="text-align:right" colspan="">Ümumi sayı</th>
	<td>
		<span><?php echo $total_stock_count; ?></span>
		<span class="mark mark--count"> ədəd</span>
	</td>							
</tr>
<?php endif;

if(is_data_access_available('th_profit')): ?>
<tr class="total_value_table">
	<th class="total_value_th" style="text-align:right" colspan="">Cəmi</th>
	<td>
		<span class="font-green"><?php echo $final_total_price; ?></span>
		<span class="mark"><?php echo $manat_image_green; ?></span>
	</td>
</tr>
<?php endif;

}



//модальное окно редактирования для отчёта report

function get_report_order_modal($give_product_id, $return_product_id, $return_product_count) {
	//если количество больше 1 то активируем инпут инчае дективируем
	if($return_product_count == 1) {
		$attr = 'disabled';
	} else {
		$attr = 'enabled';
	}


?>


<div class="module_order_wrp ls-custom-scrollbar">
	<div class="receipet_success">
		<a><img src="img/icon/print-success.png"></a>
	</div>

	<div class="modal_order_form">
		<ul>
		<?php order_filter_list_tpl('terminal', 'phone', $return_product_id); ?>		
		</ul>	
		<div class="order_modal_list return_list">
			<div class="return_modal_list">
				<span class="module_order_desrioption">Vazvrat edin</span>
				<input type="text" class="order_input return_input_action return_input_style" <?php echo $attr; ?> value="<?php echo $return_product_count; ?>" data-report-id="<?php echo $return_product_id; ?>" data-prod-id="<?php echo $give_product_id; ?>">
			</div>
			<div class="return_modal_list">
				<a href="javascript:void(0)" class="btn get_return_accept_btn get_return_accept_style">OK</a>
			</div>	
		</div>

		<div class="order_modal_list return_list">
			<span class="module_order_desrioption">Hesabatı silmək</span>
			<a href="javascript:void(0)" class="delete_btn_link btn">Silmək</a>
		</div>

		<div class="delete_stock_module">
			<div class="receipet_success">
				<a><img src="img/icon/print-success.png"></a>
			</div>
			<div class="delete_stock_form">
				<span>Silmək istədiyinizə əminsiniz ?</span>
				<div class="module_delete_btn_link">
					<a href="javascript:void(0)" class="delete_report" id="<?php echo $give_product_id; ?>"> Sil</a>
					<a href="javascript:void(0)" class="module_delete_btn_cancle"> Ləğv et</a>
				</div>
			</div>
		</div>

	</div>

</div>

<?php 
}



//подсчет количества товаров в склдае акссесуаров

function getTotalPriceSellStock($stock_type) {
	global $dbpdo;
	$arr = [];
	$getOptionNameList = [];
	$getOptionName = $dbpdo->prepare("SELECT * 
		FROM stock_list 
		WHERE stock_type = :stock_type
		AND stock_visible = 0");
	$getOptionName->execute([$stock_type]);

	while($getOptionRow = $getOptionName->fetch(PDO::FETCH_BOTH))
		$getOptionNameList[] = $getOptionRow;
	foreach ($getOptionNameList as $getOptionRow) {

		$stock_count = $getOptionRow['stock_count'];
		$stock_first_price = $getOptionRow['stock_first_price'];

		$res = $stock_count * $stock_first_price;
		
		$arr[] = $res;


	}
		echo array_sum($arr);

}


function get_product_count_price($stock_val, $stock_type, $manat_image) {
	global $dbpdo;
	$arr = [];
	$count = [];

	$getOptionNameList = [];
	$getOptionName = $dbpdo->prepare("SELECT * 
		FROM stock_list 

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

	");
	$getOptionName->bindValue('search_val_name', 	  "%{$stock_val}%"); 
	$getOptionName->bindValue('search_val_imei', 	  "%{$stock_val}%"); 
	$getOptionName->bindValue('search_val_provider',  "%{$stock_val}%"); 
	$getOptionName->bindValue('search_val_fdate', 	  "%{$stock_val}%");

	$getOptionName->bindValue('name_prod_cat', 	  	$stock_type);
	$getOptionName->bindValue('imei_prod_cat', 	  	$stock_type);
	$getOptionName->bindValue('provider_prod_cat',  $stock_type);
	$getOptionName->bindValue('fdate_prod_cat', 	$stock_type);
	$getOptionName->execute();

	while($getOptionRow = $getOptionName->fetch(PDO::FETCH_BOTH))
		$getOptionNameList[] = $getOptionRow;
	foreach ($getOptionNameList as $getOptionRow) {

		$stock_count = $getOptionRow['stock_count'];
		$stock_first_price = $getOptionRow['stock_first_price'];

		$res = $stock_count * $stock_first_price;
		
		$arr[] = $res;

		$count[] = $stock_count;

	}

	$total_price = array_sum($arr);
	$total_count = array_sum($count);

	
	echo total_value_template($total_price, $total_count, $manat_image);

}



function total_value_template($price, $count, $manat_image) {

if($price && is_data_access_available('th_buy_price')) { ?>
<tr class="total_value_table">
	<th class="total_value_th" style="text-align: right;"><span class="mark mark--total-price">Cəmi: </span></th>
	<td class="flex table_total_price-mark">
	  	<span class="stock_info_text s_result_sprice"><?php echo $price; ?></span>
	  	<span class="mark"> <?php echo $manat_image ?> </span>
	</td>
</tr>
<?php } 

if($count && is_data_access_available('th_count')) {
?>
<tr class="total_value_table">
	<th class="total_value_th" style="text-align: right;"><span class="mark mark--total-price">Say: </span></th>
	<td class="flex table_total_count-mark">
	  	<span class="stock_info_text s_result_count"><?php echo $count; ?></span>
	  	<span class="mark mark--count"> ədəd</span>	
	</td>	

</tr>

<?php 
	}
	
}



function get_total_search_some($statment) {
	$count = $statment->rowCount();
?>

<tr class="total_value_table">
	<th class="total_value_th" style="text-align:right">Tapıldı:</th>
	<td>
		<?php echo $count; ?>
		<span class="mark mark--count"> ədəd</span>	
	</td>							
</tr>
			
<?php 
}




function get_total_rasxod_value( $value ) {		

	global $dbpdo, 
		   $manat_image;

	$check_total_rasxod = $dbpdo->prepare("SELECT sum(rasxod_money) 
		as total_rasxod_money 
		FROM rasxod 
		WHERE rasxod_year_date = :mydateyear
		AND rasxod_visible = 0");
	$check_total_rasxod->bindParam('mydateyear', $value, PDO::PARAM_INT);
	$check_total_rasxod->execute();

	$check_total_rasxod_row = $check_total_rasxod->fetch();

	$total = round($check_total_rasxod_row['total_rasxod_money']);


	echo total_value_template($total, NULL, $manat_image);
}



////////deeeeeeeeeeeeeeeeeeeeeeeeeeeellllllll this updet uner this comment//////////////////


function get_note_list($get_note) {
	$note_id 			= $get_note['note_id'];
	$note_date 			= $get_note['note_date'];
	$note_name 			= $get_note['note_name'];
	$note_descrpt 		= $get_note['note_descrpt'];
	ob_start();

?>
<tr class="note_table" id="<?php echo $note_id ?>">
	<td class="note_list">
		<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text"><?php echo $note_id; ?></span>
	  	</a>
	</td>	
	<td class="note_list">
		<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text note_date_a"><?php echo $note_date; ?></span>
	  	</a>		
	</td>
	<td class="note_list">
		<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text note_name_a"><?php echo $note_name; ?></span>
	  	</a>		
	</td>
	<td class="note_list">
		<a href="javascript:void(0)" class="stock_info_link_block"> 
	  		<span class="stock_info_text note_descrpt_a"><?php echo $note_descrpt; ?></span>
	  	</a>		
	</td>
</tr>	
<?php
    $result = ob_get_clean();
 
    return $result;
}



function getReminerList() {
		global $dbpdo;
		$note_reminder_list = [];
		$note_reminer_view = $dbpdo->prepare("SELECT * FROM no_availible_order 
			WHERE order_stock_visible = 0 AND
			note_type = 'order_reminder'
			GROUP BY order_stock_id DESC");
		$note_reminer_view->execute();

		while ($order_reminder_row = $note_reminer_view->fetch(PDO::FETCH_BOTH))
			$note_reminder_list[] = $order_reminder_row;

		foreach ($note_reminder_list as $order_reminder_row)
		{ ?>
		<tr class="get_reminder_option_action_left_side reminder_list_style" id="<?php echo $order_reminder_row['order_stock_id']; ?>">
			<td class="note_table_list_w3">
				<span><?php echo $order_reminder_row['order_stock_id']; ?></span>
			</td>	
			<td class="note_table_list_w3">
				<span><?php echo $order_reminder_row['order_stock_full_date']; ?></span>
			</td>
			<td>
				<p><?php echo nl2br($order_reminder_row['order_stock_description']); ?></p>
			</td>
		</tr>
	 <?php }		
}

function getReminder() {
	global $dbpdo;
	$user_reminder_date = date("Y-m-d");
	$get_reminder_list = [];
	$getReminder = $dbpdo->prepare("SELECT * FROM no_availible_order 
			WHERE order_stock_visible = 0 
			AND note_type = 'order_reminder'
			AND order_stock_full_date = :reminder_date
			GROUP BY order_stock_id DESC");
	$getReminder->bindParam('reminder_date', $user_reminder_date, PDO::PARAM_STR);
	$getReminder->execute();

	if($getReminder->rowCount()>0) { ?>
		<div class="reminder_wrapper_header">
			 <div class="reminder_hder">
			 	<span class="notify_indcator"></span>
			 	<h3>Напоминание!</h3> 
			 </div>	
		<?php			 	
		while($get_rminder_row = $getReminder->fetch(PDO::FETCH_BOTH))
			$get_reminder_list[] = $get_rminder_row;
		foreach($get_reminder_list as $get_rminder_row) {
			?>
				<div class="reminder_list">
					<div class="reminder_list_content_wrp">
						<div class="reminder_date_cont_hdr">
							<h3><?php echo $get_rminder_row['order_stock_full_date']; ?></h3>
							<div class="close_reminder_header"><a href="javascript:void(0)" id="<?php echo $get_rminder_row['order_stock_id']; ?>" class="reminder_delete_action reminder_delete_hdr"><img src="img/icon/cancel-black.png"></a></div>
						</div>
						<div class="reminder_description_content_wrp">
							<p class="reminder_descrp_content"><?php echo nl2br($get_rminder_row['order_stock_description']) ?></p>
						</div>
					</div>
				</div>
	<?php } ?>
		</div>
	<?php 	
	}
}


function get_rasxod_tr_tamplate($get_rasxod) {

	$rasxod_id 				= $get_rasxod['rasxod_id'];
	$rasxod_day_date 		= $get_rasxod['rasxod_day_date'];
	$rasxod_price 			= $get_rasxod['rasxod_price'];
	$rasxod_descriptuon 	= $get_rasxod['rasxod_descriptuon'];
	$manat_image 			= $get_rasxod['manat_image'];
	ob_start();
?>

<tr class="rasxod_list_tr" id="<?php echo $rasxod_id; ?>">
	<td>
		<a href="javascript:void(0)" class="stock_name_box_link_btn filter_search_nr" data-sort="date">
			<span class="stock_info_text"><?php echo trim($rasxod_day_date); ?></span>	
		</a>
	</td>
	<td class="rasxod_table">
		<span class="stock_info_text rasxod_value_a"><?php echo $rasxod_price . $manat_image; ?></span>
	</td>
	<td class="rasxod_table">
		<span class="stock_info_text rasxod_descrpt_a rasxod_descrpt_s"><?php echo nl2br($rasxod_descriptuon); ?></span>
	</td>
</tr>

<?php
 $result = ob_get_clean();
 return $result;
}



function rasxod_order_tamplate($get_rasxod) {

	$rasxod_id 			=  $get_rasxod['rasxod_id']; 		
	$rasxod_vlue		=  $get_rasxod['rasxod_vlue'];		
	$rasxod_description	=  $get_rasxod['rasxod_description'];
?>
<div class="module_order_wrp ls-custom-scrollbar">

	<ul class="modal_order_form" data-order-id="<?php echo $rasxod_id; ?>">

		<li class="order_modal_list">
			<span class="module_order_desrioption">XƏRC MEBLEG:: </span>
			<span class="module_stock_span_name">
				<input type="text" class="add_stock_input note_name_upd_actinon" value="<?php echo $rasxod_vlue; ?>">		
			</span>
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">TƏSVIR: </span>
			<span class="module_stock_span_imei">
				<textarea class="add_stock_input note_descrpt_upd_action" style="width: 100%;"><?php echo $rasxod_description; ?></textarea>
			</span>
		</li>

		<li class="order_modal_list edit_btn_wrp">
			<a href="javascript:void(0)" class="btn red delete_rasxod_s delete_rasxod_action">Sil</a>
			<a href="javascript:void(0)" class="edit_upd_btn_link btn save_edit_rasxod">Saxla</a>
		</li>
	</ul>

	<div class="order_resault"></div>
</div>

<?php
}



//шаблон оформления заказа - телефон
function order_note_template_upd( $give_product_id,
								  	$note_name,
								  	$note_descrpt ) {
?>


<div class="module_order_wrp ls-custom-scrollbar">

	<ul class="modal_order_form" data-order-id="<?php echo $give_product_id; ?>">

		<li class="order_modal_list">
			<span class="module_order_desrioption">Qeyd: </span>
			<span class="module_stock_span_name">
				<input type="text" class="add_stock_input note_name_upd_actinon" value="<?php echo $note_name; ?>">		
			</span>
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">TƏSVIR: </span>
			<span class="module_stock_span_imei">
				<textarea class="add_stock_input note_descrpt_upd_action" style="width: 100%;"><?php echo $note_descrpt; ?></textarea>
			</span>
		</li>

		<li class="order_modal_list edit_btn_wrp">
			<a href="javascript:void(0)" class="btn red delete_rasxod_s delete_note_a">Sil</a>
			<a href="javascript:void(0)" class="edit_upd_btn_link btn save_edit_note">Saxla</a>
		</li>
	</ul>

	<div class="order_resault"></div>
</div>

<?php	
}

//подключаем боковое меню
function get_sidebar_menu() {
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/main/menu_sidebar.php';
}

//подключаем фильтры
function get_filter_root() {
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/pulgin/stock_filter/filter_template.php';
}


//показывает если таблиа пустая
function check_empty_result($query) { 
	if($query->rowCount() <= 0) {
		echo '<tr class="empty_table_row">
				<td>Məlumat yoxdur</td>
			</tr>';
	} 
}


//кнопка наверх
function scroll_to_top() {
?>
	<div class="scroll_to_top_wrapper btn">
		<a href="javascript:void(0)" class="scroll_top sroll_top_style" title="YUXARI "></a>
	</div>
<?php 
}

//выводим кнопки фильтра
function ger_filter_param($filter_type, $text) {
	global $dbpdo;

	$filter_btn_arr = [] ;
	$sort_name = [];

	$filter_button = $dbpdo->prepare('SELECT *  FROM filter 
		WHERE filter_type = ? 
		GROUP BY filter_value DESC  
		ORDER BY ABS(filter_value)  ASC');
	$filter_button->execute([$filter_type]);
	while($row = $filter_button->fetch(PDO::FETCH_ASSOC))
		$filter_btn_arr[] = $row;
		
		//arsort($filter_btn_arr);
		foreach ($filter_btn_arr as $row) {
			$filter_type = $row['filter_type'];		
			$filter_val = $row['filter_value'];
			$id = $row['filter_id'];
			?>
			<li class="filter-check-list-style">
				<a href="javascript:void(0)" class="filter-check-style filter-check" id="<?php echo $id; ?>" filter-type="<?php echo $filter_type; ?>" filter-val="<?php echo $filter_val; ?>">
					<span class="mark mark-filter-checked-icon"><img src="/img/icon/check-white.png"></span>
					<span class="mark filter-name"><?php echo $filter_val; ?></span>
					<span class="mark filter-mark-text"> <?php echo $text; ?></span>
				</a>	
			</li>
			<?php		   
		
		}
}

//получаем название фильтра
function get_filter_prefix_title($prefix) {
	global $dbpdo;
	$result = [];
	$filter_prefix = $dbpdo->prepare("SELECT * FROM filter_list WHERE filter_list_prefix = :prefix");
	$filter_prefix->bindParam('prefix', $prefix);
	$filter_prefix->execute();
	if($filter_prefix->rowCount() > 0) {
		$row = $filter_prefix->fetch();

		$title = $row['filter_list_title'];
		$short_name = $row['filter_short_name'];
		
		$result = array('title' => $title, 'short_name' => $short_name);
	}
	return $result;
}

//собриаем фильтр и выводим массив в шаблон
function filter_category($filter_list, $id) {
	/**example $filter_list 
	*
	*	$filter_list = array(
	*		'color',
	*		'storage',
	*		'ram'
	*	);
	*
	**/
	$total = [];
	foreach ($filter_list as $prefix) {
		//получем название фильтра и описание
		$title_row = get_filter_prefix_title($prefix);
		//список значений филтров 
		$list = get_filter_list_by_prefix($prefix);		
		// ативный фильтр
		$active = get_active_filters($prefix, $id);
		if($list && $title_row) {
			$title = $title_row['title'];
			$title_short_name = $title_row['short_name'];
			$total[] = [
				'title' => $title, 
				'short_name' => $title_short_name, 
				'prefix' => $prefix, 'active' => $active, 
				'compelte' => $list
			];
		}
		// array_push($total, array('title'=> $title, 'active' => $active, 'compelte' =>  $res));
	}
	return $total;
}


//получаем список значения фильтра
function get_filter_list_by_prefix($prefix) {
	global $dbpdo;

	$filter_btn_arr = [];
	$sort_name = [];
	$result = array();
	$total = array();

	$filter_button = $dbpdo->prepare("SELECT * FROM user_control
		INNER JOIN filter_list  ON filter_list.filter_list_prefix = :prefix
		LEFT JOIN filter ON filter.filter_type = filter_list.filter_list_id
		GROUP BY filter.filter_value DESC  ORDER BY ABS(`filter`.`filter_value`)  ASC");
	$filter_button->bindParam('prefix', $prefix);
	$filter_button->execute();
	if($filter_button->rowCount() > 0) {
		while ($row = $filter_button->fetch())
		$filter_btn_arr[] = $row;
		foreach ($filter_btn_arr as $row) { 
			$filter_id = $row['filter_id'];
			$filter_value = $row['filter_value'];
			$filter_type = $row['filter_type'];

			array_push($total, array(
				'filter_type' => $filter_type,
				'id'	=> $filter_id,
				'value' => $filter_value 
			));
	
		}	    	                 

		return $total;	
	}
}

//получем активный фильтр 
function get_active_filters($prefix, $id) {
	global $dbpdo;

	$active_filter_id = false;
	$active_filter_val = false;
	$active = false;
	$array = [];
	$get_order_filter = $dbpdo->prepare(" SELECT * FROM user_control

		INNER JOIN stock_filter ON stock_filter.stock_id = :id 

		INNER JOIN filter_list ON filter_list.filter_list_prefix = :prefix

		INNER JOIN filter ON filter.filter_type = filter_list.filter_list_id

		AND stock_filter.active_filter_id = filter.filter_id

		");			
	$get_order_filter->bindParam('id', $id);
	$get_order_filter->bindParam('prefix', $prefix);
	$get_order_filter->execute();
	$get_filter_row = $get_order_filter->fetch();

	if($get_order_filter->rowCount()>0) {
		$active_filter_id = $get_filter_row['filter_id']; 
		$active_filter_val = $get_filter_row['filter_value']; 
		$active = 'actived';
		$array[] = ['res' => $active, 'filter_id' => $active_filter_id, 'filter_val' => $active_filter_val];
	} 
	return $array;
}




function order_filter_list_tpl($action, $prod_cat, $give_product_id) {
	$arr  = array(
		'color' 	=> 'Rəng',
		'storage'   => 'Yaddaş',
		'ram'		=> 'Ram',
		'used' 		=> 'Yeni/İşlənmiş'
	);
		foreach ($arr as $key => $value) {
		$custom_class_filter_input = '';

			$filter_name = $key;
			$filter_title = $value;

			// если вкладка терминал
			if($action === 'terminal') {
				if($filter_name == 'color') 	{ $custom_class_filter_input = 'get_product_color'; $mark = ''; }
				if($filter_name == 'storage') 	{ $custom_class_filter_input = 'get_product_storage'; $mark = 'GB'; }
				if($filter_name == 'ram') 		{ $custom_class_filter_input = 'get_product_ram'; $mark = 'GB'; }
				if($filter_name == 'used') 		{ $custom_class_filter_input = 'get_product_used'; $mark = ''; }					
				//если телефон
				if($prod_cat === 'phone') {
					terminal_order_list_tpl($filter_title, $filter_name, $give_product_id, $custom_class_filter_input, $mark);
				}
				//если аксс
				if($prod_cat === 'akss') {
					terminal_order_list_tpl($filter_title, $filter_name, $give_product_id, $custom_class_filter_input, $mark);
				}
			}

			if($action == 'stock') {
				if($filter_name == 'color') 	{ $custom_class_filter_input = 'upd_product_color'; $mark = '';}
				if($filter_name == 'storage') 	{ $custom_class_filter_input = 'upd_product_storage'; $mark = 'GB';}
				if($filter_name == 'ram') 		{ $custom_class_filter_input = 'upd_product_ram'; $mark = 'GB';}
				if($filter_name == 'used') 		{ $custom_class_filter_input = 'upd_product_used'; $mark = '';}	

				if($prod_cat === 'phone') {
					stock_edit_order_list_tpl($filter_title, $filter_name, $give_product_id, $custom_class_filter_input, $mark);
				}
				//если аксс
				if($prod_cat == 'akss') {
					stock_edit_order_list_tpl($filter_title, $filter_name, $give_product_id, $custom_class_filter_input, $mark);
				}				
			}


			if($action == 'terminal-filter') {
				if($filter_name == 'color') 	{ $custom_class_filter_input = 'search_product_color'; $mark = '';}
				if($filter_name == 'storage') 	{ $custom_class_filter_input = 'search_product_storage'; $mark = 'GB';}
				if($filter_name == 'ram') 		{ $custom_class_filter_input = 'search_product_ram'; $mark = 'GB';}
				if($filter_name == 'used') 		{ $custom_class_filter_input = 'search_product_used'; $mark = '';}

				terminal_filter_return_tpl($filter_title, $filter_name, $custom_class_filter_input, $mark);
			}

		}
}




//массив с данными фильтра, очень криво и косо, лучше сюда не смотреть можете потерять доверия ко мне 
function add_filter_list_tpl() {
	$arr  = array(
		'color' 	=> 'Rəng',
		'storage'   => 'Yaddaş',
		'ram'		=> 'Ram',
		'used' 		=> 'Yeni/İşlənmiş'
	);
		$custom_class_filter_input = '';
		foreach ($arr as $key => $value) {

			$filter_name = $key;
			$filter_title = $value;

			if($filter_name == 'color') 	{ $custom_class_filter_input = 'get_product_color'; $mark = ''; }
			if($filter_name == 'storage') 	{ $custom_class_filter_input = 'get_product_storage'; $mark = 'GB'; }
			if($filter_name == 'ram') 		{ $custom_class_filter_input = 'get_product_ram'; $mark = 'GB'; }
			if($filter_name == 'used') 		{ $custom_class_filter_input = 'get_product_used'; $mark = ''; }

		?>

		<li class="add_stock_form_list row-list">
			<span class="add_stock_description"><?php echo $filter_title ?></span>
			<div class="ls-custom-select-wrapper">
				<ul class="ls-select-list">
					<div class="select-drop-down">
						<input type="button"  id="" class="<?php echo $custom_class_filter_input; ?> drop_down_btn" value="seçin" default-value="seçin">
						<div class="reset_option">
							<input type="button" class="ls-reset-option ls-reset-option-style">
						</div>
					</div>
					<div class="ls-select-option-list">
						<ul class="ls-select-list-option ls-custom-scrollbar">
							<?php get_filter_list_by_prefix($filter_name, '', $mark); ?>		
						</ul>
					</div>
				</ul>
			</div>
		</li>
	 <?php } 	
}

//получить информацию товара по id
function get_product_by_id($arr) {
	/**example
	*	$arr =  array(
	*		'id' => $id,
	*		'action' => 'get_name'
	*	);
	*/

	global $dbpdo;

	$id = $arr['id'];
	$action = $arr['action'];

	$get_prod = $dbpdo->prepare('SELECT * FROM stock_list WHERE stock_id = ?');
	$get_prod->execute([$id]);

	$row = $get_prod->fetch(PDO::FETCH_ASSOC);

	switch ($action) {
		case 'name':
			return $row['stock_name'];
			break;
		case 'imei':
			return $row['stock_phone_imei'];
			break;
		case 'provider':
			return $row['stock_provider'];
			break;
		case 'category':
			return $row['stock_provider'];
			break;			
		case 'first_price':
			return $row['stock_first_price'];
			break;
		case 'all':
			return $row['stock_name'];
			break;												
		
		default:
			# code...
			break;
	}

}


//дебаг функция
function ls_var_dump($var) {
	echo "<pre>";
		print_r($var) ;
	echo "</pre>";
}



/**  обновляем фильтры продукта или добавляем
*	 example
*
*	$param = array(
*		'stock_id' => $id,
*		'filter_id' => array(id, id, id)
*	);
**/
function ls_update_filter($param) {
	global $dbpdo;

	foreach ($param as $stock => $row) {
		$stock_id = $row['stock_id'];

		ls_reset_filter($stock_id);
		
		foreach ($row['filter'] as $key => $filter_id) {
			ls_insert_filter($stock_id, $filter_id);
		}		
	}
}

//добавляем фильтры пользователя
function ls_insert_filter($stock_id, $filter_id) {
	global $dbpdo;
	$insert_filter = $dbpdo->prepare("INSERT INTO stock_filter (stock_id, active_filter_id) VALUES (?, ?) ");
	$insert_filter->execute([$stock_id, $filter_id]);	
}
//сбрасываем фильры пользователя
function ls_reset_filter($stock_id) {
	global $dbpdo;
	$reset_filter = $dbpdo->prepare('DELETE FROM stock_filter WHERE stock_id = :stock_id');
	$reset_filter->bindParam('stock_id', $stock_id);
	$reset_filter->execute();	
}




//тут описываем страницы 
function get_tab_main_page_test() {
	$menu_list = [
		'terminal' =>	[
			'title' 			=> 'Əməliyyatlar',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-store-alt'
			],
			'link'  			=> '/page/base.php',
			'template_src'      => 'page/base_tpl.twig',
			'background_color'  => 'rgba(0, 150, 136, 0.1)',
			'tab' => array(
				'list' => [
					'tab_test',
					'tab_terminal_phone',
					'tab_terminal_akss'	 					
				],
				'active' => 'tab_test'
			)
		],	
		'stock' =>	[
			'title'		 		=> 'Anbar',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-boxes'
			],
			'link'  			=> '/page/base.php',		
			'template_src'      => '/page/base_tpl.twig',
			'background_color'  => 'rgba(72, 61, 139, 0.1)',
			'tab' => array(
				'list' => [
					'tab_stock_phone',
					'tab_stock_akss'
				],
				'active' => 'tab_stock_phone'			
			)
		],	
		'report' => [
			'title' 			=> 'Hesabat',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-coins'
			],
			'link'  			=> '/page/base.php',
			'template_src'		=> '/page/base_tpl.twig',
			'background_color'  => 'rgba(33, 150, 243, 0.1)',			
			'tab' => array(
				'list'=> [
					'tab_report_phone', 
					'tab_report_akss'
				],
				'active' => 'tab_report_phone'
			
			)
		],	
		'admin' => [
			'title' => 'Admin',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-user-lock'
			],
			'link'  => '/page/admin/admin.php',
			'background_color' => 'rgba(255, 48, 48, 0.1)',
			'default_tab' => '',			
			'tab' => array(
								
			)
		],			
		'note' => [
			'title' => 'Notlar',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'lar la-sticky-note'
			],
			'link'  => '/page/note/note.php',
			'background_color' => 'rgba(255, 255, 101, 0.1)',
			'default_tab' => '',			
			'tab' => array(
								
			)
		],	
		'rasxod' => [
			'title' => 'Xərc (Rasxod)',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-file-invoice-dollar'
			],
			'link'  => '/page/rasxod/rasxod.php',
			'background_color' => 'rgba(255, 48, 48, 0.1)',
			'default_tab' => '',			
			'tab' => array(
								
			)
		]
	];
	return $menu_list;
}

//тут описываем вкладки и страницы
function get_tab_data($key = null, $active = null) {
	$result = [];

	$tab_arr = array(
		'tab_terminal_phone' => array(
			'type'				=> 'phone',
			'tab_title'			=> 'Telefonlar',
			'tab_link' 			=> '/page/terminal/terminal.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_terminal_akss' => array(
			'type'				=> 'akss',
			'tab_title' 		=> 'DigƏr',
			'tab_link' 			=> '/page/terminal/terminal.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => '' 
		),
		'tab_stock_phone' => array(
			'type'				=> 'phone',
			'tab_title'			=> 'Telefonlar',
			'tab_link' 			=> '/page/stock/stock.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_stock_akss' => array(
			'type'				=> 'akss',
			'tab_title' 		=> 'DigƏr',
			'tab_link' 			=> '/page/stock/stock.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_report_phone' => array(
			'type'				=> 'phone',
			'tab_title'			=> 'Telefonlar',
			'tab_link' 			=> '/page/report/report.php',
			'tab_icon' 			=> '/img/icon/investment.png',
			'tab_modify_class'  => ''		
		),
		'tab_report_akss' => array(
			'type'				=> 'akss',
			'tab_title'			=> 'DigƏr',
			'tab_link' 			=> '/page/report/report.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_test' => array(
			'type' => 'phone',
			'tab_title' => 'Admin panel',
			'tab_link' => '/page/terminal/terminal.php',
			'tab_icon' => false,
			'tab_mofify_class' => false
		)


		
		// 'tab_stock_phone' => array(
		// 	'title' => 'Telefonlar',
		// 	'tab_link' => '/page/stock/stock_phone.php',
		// 	'icon' => '' 
		// ),
		// 'tab_stock_akss' => array(
		// 	'title' => 'Digər',
		// 	'tab_link' => '/page/stock/stock_akss.php',
		// 	'icon' => '' 
		// ),
		// 'tab_report_phone' => array(
		// 	'title' => 'Telefonlar',
		// 	'tab_link' => '/page/report/report_phone.php',
		// 	'icon' => '' 
		// ),
		// 'tab_report_akss' => array(
		// 	'title' => 'Digər',
		// 	'tab_link' => '/page/report/report_akss.php',
		// 	'icon' => '' 
		// ),
		// 'tab_debt_history' => array(
		// 	'title' => 'История кредита',
		// 	'tab_link' => 'helloworld.php',
		// 	'icon' => '' 

		// ),
		// 'tab_debt_transaction' => array(
		// 	'title' => 'История Платижей',
		// 	'tab_link' => 'helloworld2.php',
		// 	'icon' => '' 

		// ),
		// 'tab_note_order' => array(
		// 	'title' => 'SIFARIŞLƏR',
		// 	'tab_link' => '/page/note/note_order.php',
		// 	'icon' => ''  
		// ),
		// 'tab_note_reminder' => array(
		// 	'title' => 'Xatırlatma',
		// 	'tab_link' => '/page/note/reminder.php',
		// 	'icon' => ''  
		// ),
		// 'tab_rasxod' => array(
		// 	'title' => 'Xərc',
		// 	'tab_link' => '/page/rasxod/rasxod_list.php',
		// 	'icon' => ''  
		// ),
		// 'tab_admin_user' => array(
		// 	'title' => 'İstifadəçilər',
		// 	'tab_link' => '/page/admin/user_list.php',
		// 	'icon' => '<img src="/img/icon/advanced.png" class="tab_icon">' 
		// )	
		 
	);

	if(!empty($key)) {
		foreach ($key as $row => $value) {

			$result[$value] = $tab_arr[$value];
			//если значение таба активна, то присваиваем вкладке класс модификатор активной вкладки
			if($active) {
			   $result[$active]['tab_modify_class'] = 'tab_activ';
			}
	   }
	   return $result;
	} else {
		return $tab_arr;
	}
	

}



function collect_product_data($stock_list, $data_name) {
	global $manat_image,
	$manat_image_green, 
	$stock_return_image;


	$result 	= [];
	$th_list 	= [];
	$complete 	= [];

	$th 		= get_th_list();		

	// ls_var_dump($stock_list);

	foreach ($data_name as $td_list => $td_row) {

		$th_this		 	= $th[$td_list];
		$th_title 			= $th_this['is_title'];
		$th_modify_class 	= $th_this['modify_class'];
		$td_class 			= $th_this['td_class'];
		$link_class 		= $th_this['link_class'];
		$data_sort 			= $th_this['data_sort'];
		$mark 				= $th_this['mark'];

		if($th_title) {
			
			$th_list[] = [
				'title' => $th_title,
				'modify_class' => $th_modify_class
			];
		
		
			$mass = [];
			foreach ($stock_list as $key => $row) {
				//fix return	
				($row['stock_return_status'] == 1) ? $row['stock_return_status'] = ' ' : $row['stock_return_status'] = false;
				
				// $result[$row['stock_id']][] = [
				// 	'data' 			=> $row[$td_row],
				// 	'td_class' 		=> $td_class,
				// 	'link_class' 	=> $link_class,
				// 	'data_sort' 	=> $data_sort,
				// 	'mark'			=> $mark
				// ];

				$result[$key][$row['stock_id']][] = [
					'data' 			=> $row[$td_row],
					'td_class' 		=> $td_class,
					'link_class' 	=> $link_class,
					'data_sort' 	=> $data_sort,
					'mark'			=> $mark
				];
			}
		}
	}

	$complete = [
		'th' => $th_list,
		'td' => $result
	];
	return $complete;	
}


function get_th_list() {
global $manat_image,
		$manat_image_green, 
		$stock_return_image;

		$th_list = [
			'id' => array(
				'is_title' 			=> check_th_return_name('th_serial'),
				'modify_class'	 	=> 'th_w40',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)				
			),
			'name' => array(
				'is_title'  		=> check_th_return_name('th_prod_name'),
				'modify_class' 		=> 'th_w250',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both res_name filter-hotkey-sort',
				'data_sort' 		=> 'name',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_title'		=> 'axtar',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => ''
					)
				)	
			),									
			'imei'  => array( 
				'is_title' 			=> check_th_return_name('th_imei'),
				'modify_class' 		=> 'th_w250',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both res_imei',
				'data_sort' 		=> 'imei',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'first_price' => array(
				'is_title'  		=> check_th_return_name('th_buy_price'),
				'modify_class'		=> 'th_w80',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both res_fprice',
				'data_sort' 		=> '',
				'mark_text' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class'
					)
				)	
			),
			'second_price' => array(
				'is_title'  		=> check_th_return_name('th_sale_price'),
				'modify_class'		=> 'th_w80',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both res_sprice',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_title'		=> 'hekk',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class'
					)
				)	
			),	
			'provider' => array(
				'is_title'  		=> check_th_return_name('th_provider'),
				'modify_class'		=> 'th_w200',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_provider',
				'data_sort' 		=> 'provider',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'return_status' => array(
				'is_title'  		=>  check_th_return_name('th_return'),
				'modify_class'		=> 'th_w40',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_title'		=> 'Bu mall vazvrat olunub',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/investment.png',
						'icon_class' => 'mark--return-icon-size'
					)
				)
			),												
			'count' => array(
				'is_title' 			=> check_th_return_name('th_count'),
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> 'ədəd',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'category' => array(
				'is_title' 			=> check_th_return_name('th_category'),
				'modify_class' 		=> 'th_w200',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-filter get_item_by_filter res_provider',
				'data_sort' 		=> 'provider',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'stock_add_date' => array(
				'is_title' 			=> check_th_return_name('th_buy_day'),
				'modify_class' 		=> 'th_w120',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-filter get_item_by_filter res_buy_date',
				'data_sort' 		=> 'buy_date',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)					
			),
			'sales_date' => array(
				'is_title' 			=> check_th_return_name('th_day_sale'),
				'modify_class' 		=> 'th_w120',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-filter get_item_by_filter res_buy_date',
				'data_sort' 		=> 'buy_date',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)					
			),
			'report_note' => array(
				'is_title' 			=> check_th_return_name('th_report_note'),
				'modify_class' 		=> 'th_w120',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> ' stock-link-text-both res_note',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)					
			),
			'report_profit' => array(
				'is_title' 			=> check_th_return_name('th_profit'),
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class',
						'icon_title' => ''
					)
				)					
			),					
			'report_date_year' => array(
				'is_title' 			=> false,
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> 'date_year',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class',
						'icon_title' => ''
					)
				)					
			),
			'report_order_id' => array(
				'is_title' 			=> check_th_return_name('th_report_serial'),
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> 'table_stock',
				'link_class' 		=> 'stock-link-text-both get_report_order_id',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => false,
						'icon_class' => false,
						'icon_title' => false
					)
				)					
			),					 			  					
		];
	
	return $th_list;
}

// //шаблоны списка товаров
// $arr = array(

// );
function ls_db_request($arr, $query) {
	/**
	 * функция принимает 2 агрумента в виде массива
	 * внутри первого массива, массив `request` который имеет ключи `param` и массив `bindList`
	 * 	param - параметр запроса, в котором указыается сам запрос к базе без указания названия табицы и перечисления столбцов
	 * 	массив bindList  - данные(переменные) которые используются в запросе заносим в этот массив
	 * внутри второго массива ключи table_name, base_query, sort_by
	 * table_name - Название таблицы
	 * base_query - Дефолтный sql запрос к таблице в котором указываем навазние таблицы и все поля
	 * sort_by - указваем сортировку, можно оставить пустым 
	 */	

	global $dbpdo;
	$stock_list 		= [];
	$result 			= [];
	$conditions 		= [];
	$table_name 		= $query['table_name'];
	$base_query 		= $query['base_query'];
	$sort_by			= $query['sort_by'];
	// ls_var_dump($query);

	// ls_var_dump($arr);
	// ls_var_dump($query);


	$query 	= $base_query;
	foreach($arr as $q => $query_row) {

		$param = $query_row['param'];
		// ls_var_dump($param);
		$bind_list = $query_row['bindList'];
		$query .= $param;
		$conditions = array_merge($conditions, $bind_list);		
	}
	$query .= $sort_by;

	// ls_var_dump($query);
	$stock_view = $dbpdo->prepare($query);	

	foreach($conditions as $bind_key => $bindValue) {
		$stock_view->bindValue($bind_key, $bindValue);
	}

	$stock_view->execute();
	// ls_var_dump($query);
		while ($row = $stock_view->fetch(PDO::FETCH_BOTH)) {	
			$result[] = $row;
		}
	return  $result;	
}
 
function ls_db_upadte($option, $POST) {
	global $dbpdo;


	$before 	= $option['before'];
	$after 		= $option['after'];
	$post_list  = $option['post_list'];
	$conditions = '';
	foreach($post_list as $post_key => $post_value) {
		if(array_key_exists($post_key, $_POST)) {
			if(array_key_exists('require', $post_value)) {
				if(empty($_POST[$post_key])) {
					return json_encode([
						'error' => 'Заполните все обязательные поля!'
					]);
				}
			}

			if($post_value['query']) {
				$conditions[] = $post_value['query'];
			}
			
			if($post_value['bind']) {
				$bind_list[$post_value['bind']] = $_POST[$post_key];
			}
		}
	}
	if($conditions) {
		$conditions = implode($conditions, ", ");
	}
	

	$query = $before;
	$query .= $conditions;
	$query .= $after;

	// ls_var_dump($bind_list);
    try {
		$update = $dbpdo->prepare($query);
	
		foreach($bind_list as $bind_key => $bind_value) {
			$update->bindValue($bind_key, $bind_value);
		}
		$update->execute();
	
		return json_encode([
			'success' => 'ok'
		]);
	} catch(PDOException $e) {
		return json_encode([
			'error' => 'Ошибка' . $e
		]);
	}
}

function default_data_param_sql($arr) {
	$page = $arr['page'];
	$type = $arr['type'];
	$param = [];

	if($page == 'terminal') {
		$table_name = 'stock_list';	
		$base_query = " SELECT * FROM user_control
		INNER JOIN stock_list ON stock_visible != 3 ";
		switch($type) {
			case 'phone':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_type = :stock_type 
									  AND stock_count > 0  
									  AND stock_visible = 0 ",					   
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_list.stock_id DESC ORDER BY stock_list.stock_id DESC "
				);
				break;
			case 'akss':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_type = :stock_type 
									  AND stock_count > 0  
									  AND stock_visible = 0  " ,
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_list.stock_id DESC ORDER BY stock_list.stock_id DESC "
				);
				break;				
		}
	}

	if($page == 'stock') {	
		$table_name = 'stock_list';	
		$base_query = " SELECT * FROM user_control 
						INNER JOIN stock_list ON stock_list.stock_visible != 3";
		switch($type) {
			case 'phone':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_list.stock_type = :stock_type 
									  AND stock_list.stock_count > 0  
									  AND stock_list.stock_visible = 0 " ,
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " ORDER BY stock_list.stock_id DESC"	
				);
				break;
			case 'akss':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_type = :stock_type 
									AND stock_count >= 0  
									AND stock_visible = 0 " ,
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " ORDER BY stock_id DESC"	
				);
				break;				
		}
	}	

	if($page == 'report') {	
		$table_name = 'stock_list, stock_order_report';
		$base_query = "SELECT * FROM user_control 
						INNER JOIN stock_list ON stock_list.stock_id != 0
						
					    INNER JOIN stock_order_report ON  stock_order_report.stock_order_visible = 0
					   ";			
		switch($type) {
			case 'phone':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_list.stock_type = :stock_type
									  AND stock_order_report.stock_id = stock_list.stock_id
									  AND stock_order_report.order_stock_count > 0 ",
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_order_report.order_stock_id DESC
					ORDER BY stock_order_report.order_stock_id DESC"	
				);
				break;
			case 'akss':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_list.stock_type = :stock_type
									  AND stock_order_report.stock_id = stock_list.stock_id
									  AND stock_order_report.order_stock_count > 0 ",
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_order_report.order_stock_id DESC
								   ORDER BY stock_order_report.order_date DESC"
					
				);
				break;				
		}
	}	

	$get_param = false;
	$get_sort  = false;

	if(array_key_exists('query', $param)) {
		$get_param = $param['query'];
	}
	if(array_key_exists('sort_by', $param)) {
		$get_sort = $param['sort_by'];
	}
	// return $param;
	return [
		 'param' 		=> $get_param,
		 'sort_by' 		=> $get_sort,
		 'table_name' 	=> $table_name,
		 'base_query' 	=> $base_query
	];
}

//тут описываем какие данные нужно выводить для кажждо категории, нужно тут описывать
function page_data_list($arr) {
	$page = $arr['page'];
	$type = $arr['type'];


	if($page == 'terminal') {
		if($type == 'phone') {

			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'name'			 	=> 'stock_name',
					'imei' 				=> 'stock_phone_imei',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'provider' 			=> 'stock_provider',
					'return_status' 	=> 'stock_return_status'
				],
				'table_total_list' => [
					'total_count'
				],
				'modal' => [
					'template_block' => 'terminal_order',
					'modal_fields' => [
						'user',
						'stock_name',
						'stock_imei',
						'stock_provider',
						'order_first_price',
						'spoiler_filter',
						'order_note',
						'order_hidden_count',
						'order_second_price',
						'order_total_amount',
						'order_submit'
					]
				],
				'filter_fields' => [
					'color',
					'used',
					'storage',
					'ram',
					'brand'
				]
			];	
		}
	
		if($type == 'akss') {		
			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'name'			 	=> 'stock_name',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'count'				=> 'stock_count',
					'category' 			=> 'stock_provider'	
				],
				'table_total_list' => [
					'total_count'
				],
				'modal' => [
					'template_block' => 'terminal_order',
					'modal_fields' => [
						'user',
						'stock_name',
						'stock_provider',
						'spoiler_filter',
						'order_note',
						'order_stock_count',
						'order_second_price',
						'order_total_amount',
						'order_submit'
					]					
				],
				'filter_fields' => [
					'color',
					'brand'
				]
			];
		}

	}


	if($page == 'stock') {
		if($type == 'phone') {
			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'stock_add_date'	=> 'stock_get_fdate',
					'name'			 	=> 'stock_name',
					'imei' 				=> 'stock_phone_imei',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'provider' 			=> 'stock_provider',
					'return_status'		=> 'stock_return_status'
				],
				'table_total_list'	=> [
					'total_count',
					'total_first_price_sum',
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'user',
						'edit_stock_name',
						'edit_stock_imei',
						'edit_stck_provider',
						'edit_stock_first_price',
						'edit_stock_second_price',
						'edit_stock_hidden_count',
						'edit_filter_spoiler',
						'edit_save'
					)					
				],
				'filter_fields' => [
					'color',
					'storage',
					'ram',
					'brand'
				],
				'form_fields_list' => array(
					'name'			=> true,
					'imei'			=> true,
					'first_price'	=> true,
					'hidden_count'	=> true,
					'second_price'	=> true,
					'provider'	    => true
				)				

			];	
		
		}
	
		if($type == 'akss') {
			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'stock_add_date'	=> 'stock_get_fdate', 	
					'name'			 	=> 'stock_name',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'count'				=> 'stock_count',
					'category' 			=> 'stock_provider'						
				],
				'table_total_list' => [
					'total_count',
					'total_first_price_sum'
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'user',
						'edit_stock_name',
						'edit_stock_category',
						'edit_stck_provider',
						'edit_stock_first_price',
						'edit_stock_second_price',
						'edit_stock_count',
						'edit_filter_spoiler',
						'edit_save'
					)					
				],
				'filter_fields' => [
					'color',
					'brand'
				],
				'form_fields_list' => array(
					'name'			=> true,
					'first_price'	=> true,
					'count'			=> true,
					'second_price'	=> true,
					'provider'	    => true
				)
			];			
		}
				
	}

	if($page == 'report') {		
		if($type == 'phone') {
			$res = [
				'get_data' => [
					'report_date_year' 	=> 'order_my_date',
					'report_order_id' 	=> 'order_stock_id',
					'sales_date'		=> 'order_date',
					'name'			 	=> 'order_stock_name',
					'imei'				=> 'stock_phone_imei',
					'second_price'		=> 'order_stock_sprice',
					'provider'			=> 'stock_provider',
					'report_note'		=> 'order_who_buy',
					'count'				=> 'order_stock_count',
					'report_profit'		=> 'order_total_profit',
				],
				'table_total_list'	=> [
					'total_count',
					'total_first_price_sum',
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'user',
						'report_order_id',
						'stock_name',
						'stock_imei',
						'stock_provider',
						'report_order_price',
						'report_order_count',
						'order_total_amount',
						'report_save_edit'
					)
				],
				'filter_fields' => [
					'color',
					'brand'
				],				

			];								
		}
	
		if($type == 'akss') {
			$res = [
				'get_data' => [
					'report_date_year'  => 'order_my_date',
					'report_order_id' 	=> 'order_stock_id',
					'sales_date'		=> 'order_date',
					'name'			 	=> 'order_stock_name',
					'second_price'		=> 'order_stock_sprice',
					'category'			=> 'stock_provider',
					'report_note'		=> 'order_who_buy',
					'count'				=> 'order_stock_count',
					'report_profit'		=> 'order_total_profit',
				],
				'table_total_list'	=> [
					'total_first_price_sum',
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'name',
						'report_order_id',
						'report_order_count',
						'report_save_edit'
					)
				],
				'filter_fields' => [
					'color',
					'brand'
				],				

			];					
	
		}					
	}

	// ls_var_dump($res);
	return $res;
}


// в этой функцие описываем какие данные таблицы нужны для определённой категории
// пример вызова функции
/**
 * 	$arr = array(
 * 	 'type' => 'phone/akss/..'         - обьязательное поле 
 * 	 'page => 'terminal/stock/report'  - обьязательное поле
 * 	'search' => array(                 - не обьязательное поле
 * 	 'param' =>  " AND stock_type = :stock_type AND stock_count > 0 " ,
 *		'bindList' => array(
 *			':stock_type' => $type
 *		)
 *	)
 * 	);
**/
function render_data_template($arr) {
	//категория
	$type = $arr['type'];
	//страница
	$page = $arr['page'];
	$data = default_data_param_sql([
		'type' => $type,
		'page' => $page
	]);

	// ls_var_dump($data);

	if(array_key_exists('search', $arr)) {
		$param[] = $arr['search'];
	}

	if(array_key_exists('search_sort_by', $arr)) {
		$order_sort = $arr['search_sort_by'];
	} elseif(array_key_exists('sort_by', $data)) {
		$order_sort = $data['sort_by'];
	} else {
		$order_sort = false;
	}


	$param[] = $data['param'];
	


	$table_name = $data['table_name'];
	$base_query = $data['base_query'];
	
	$get_data = page_data_list([
		'type' => $type,
		'page' => $page
	]);


	$query = [
		'table_name' => $table_name,
		'base_query' => $base_query,
		'sort_by'	 => $order_sort
	];

	// ls_var_dump($query);
	// ls_var_dump($param);
	
	$stock_list = ls_db_request($param, $query);

// ls_var_dump($stock_list);

	return [
		'result' => collect_product_data($stock_list, $get_data['get_data']),
		'base_result' => query_clear_by_user_access([ 'query' => $stock_list, 'access' => $get_data ])
	]; 	

}

//количество товаров 
function get_table_result_row_count($arr) {
	$total_count = [];
	foreach($arr as $stock) {
		$total_count[] = $stock['stock_count'];
	}

	return [
		'title' => 'Tapıldı',
		'value' => array_sum($total_count),
		'mark' 	=> 'ədəd'
	];
}

//сумма себестоимости
function get_stock_first_price_sum($stock_list) {
	$total_sum = [];
	foreach($stock_list as $key) {
		if(array_key_exists('stock_first_price', $key) && array_key_exists('stock_count', $key)) {
			$total_sum[] = $key['stock_first_price'] * $key['stock_count'];
		}	
	}
	return [
		'title' => 'Malların ümumi dəyəri',
		'value' => array_sum($total_sum),
		'mark' 	=> '',
		'mark_icon' => '/img/icon/manat.png'
	];	
}

//вызываем данные для футреа таблицы
function get_table_total($arr) {
	if($arr && $arr['total_list']) {
		$total_list = $arr['total_list'];
		$stock_list = $arr['data'];
	
		foreach($total_list as $key) {
			if($key == 'total_count') {
				$res[] = get_table_result_row_count($stock_list);
			}
			if($key == 'total_first_price_sum') {
				$res[] = get_stock_first_price_sum($stock_list); 
			}
		}
		return $res;
	}
}




// получаем список поставщиков
function get_provider_list() {	
	$provider = ls_db_request( 
		array(
			'request' => [
				'param' => ' AND stock_type = :stock_type AND stock_count > 0   AND stock_visible = 0 ',
				'bindList' => array(
					'stock_type' => 'phone'
				)
			]
		),
		array(
			'table_name' => 'stock_list',
			'base_query' => 'SELECT DISTINCT stock_provider FROM stock_list WHERE stock_visible != 3  ',
			'sort_by' 	 => ' ORDER BY stock_id DESC  '	
		)
	);


	foreach($provider as $key => $row) {
		$provider_list[] = $row['stock_provider'];
	}

	return $provider_list;
}


function get_report_date_list($type) {
	$res = ls_db_request(
		array(
			'request' => [
				'param' => " AND stock_type = :stock_type  AND order_stock_count > 0 ",
				'bindList' => array(
					'stock_type' => $type
				)
			]
		),
		array(
			'table_name' => 'stock_order_report',
			'base_query' =>  "SELECT DISTINCT order_my_date FROM stock_order_report WHERE stock_order_visible = 0 ",
			'sort_by' => ' ORDER BY order_real_time desc '
		)
	);

	foreach($res as $key => $row) {
		$dd[] = $row['order_my_date'];
	}

	return $dd;
}

