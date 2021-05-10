<?php 
//шаблон для оформения заказа на странице терминала - лист
function terminal_order_list_tpl($filter_title, $filter_name, $give_product_id, $custom_class_filter_input, $mark) {
?>	
	<li class="order_modal_list">
		<span class="module_order_desrioption"><?php echo $filter_title; ?></span>
		<?php get_filter_selected_input_value($filter_name, $give_product_id, $custom_class_filter_input, 'disabled', 'terminal', $mark); ?>
	</li>
<?php	
}


//шаблон для редакрирования товара на стрнаице амбар - лист
function stock_edit_order_list_tpl($filter_title, $filter_name, $edit_stock_id, $custom_class_filter_input, $mark) {
?>
		<li class="order_modal_list">
			<span class="module_order_desrioption"><?php echo $filter_title; ?></span>
			<div class="add_stock_filter_redit">
				<div class="ls-custom-select-wrapper">
					<ul class="ls-select-list">
						<div class="select-drop-down">

						<?php get_filter_selected_input_value($filter_name, $edit_stock_id, $custom_class_filter_input, '', 'stock', $mark); ?>

							<div class="reset_option">
								<input type="button" class="ls-reset-option ls-reset-option-style">
							</div>
						</div>
						<div class="ls-select-option-list">
							<ul class="ls-select-list-option ls-custom-scrollbar">
								<?php get_filter_li_list($filter_name, '', ''); ?>		
							</ul>
						</div>
					</ul>
				</div>				
			</div>
		</li>
<?php			
}

//вывод списка филтров для страницы терминал
function terminal_filter_return_tpl($filter_title, $filter_name, $class, $text) {
?>
	<li class="filter_return_list ls-select-list">
		<div class="filter_check_count"></div>
		<div class="select-drop-down">
			<input type="button"  class="<?php echo $class; ?> drop_down_btn filter_input_height_modify" value="<?php echo $filter_title; ?>" default-value="<?php echo $filter_title; ?>">
			<div class="reset_option">
				<input type="button" class="ls-reset-option ls-reset-option-style">
			</div>
		</div>
		<div class="ls-select-option-list">
			<ul class="ls-select-list-option ls-custom-scrollbar">
				<?php ger_filter_param($filter_name, $text); ?>		
			</ul>
		</div>

	</li>
<?php		
}

function auto_complete_select_wrapper_tpl() {
?>
	<div class="auto-cmplt-select auto-compelete-list-style">
		<ul class="ls-select-list-option ls-custom-scrollbar auto-cmplt-result">
			<?php get_autocomplete_list_tpl('', '', '', ''); ?>
		</ul>
	</div>
<?php
}


function get_autocomplete_list_tpl($arr) {


	if(empty($arr['value'])) { echo '<span class="">Начните вводить название</span>'; }
	else {
		$list_value = $arr['value'];
		$custom_class = $arr['class'];
		$data_sort = $arr['attr-data-sort'];
		$text = $arr['text'];
		$id = $arr['id'];
		$mark_class = $arr['mark_class'];
?>	


	<li class="ls-select-li">
		<a href="javascript:void(0)" 
		   class="choice-style auto-cmplt-list <?php echo  $custom_class; ?>" 
		   id="<?php echo $id; ?>" 
		   value="<?php echo $list_value; ?>" 
		   data-sort="<?php echo $data_sort; ?>">
			<span class="mark filter-mark-icon icon"><img src="/img/icon/search-loupe.png"></span>
			<span class="mark filter-name <?php echo $mark_class; ?>"><?php echo $list_value; ?></span>
			<span class="mark filter-mark-text"><?php echo $text; ?></span>
		</a>
	</li>
<?php
	}
}


//кнопка для доп функциий на вклдаке отчета (report) шаблон
function advanced_option_load_tpl() {
?>
<div class="filter_buttons_wrapper">
	<div class="filter_block_open_wrp">
		<a href="javascript:void(0)" class="filter_wodjet_button_style open_filter_widjet load_advanced_report">
			<span class="mark mark--img"><img src="/img/icon/advanced.png"></span>
			<span class="mark mark--name filter_widjet_btn_title">Digər</span>
			<span class="mark filter-count"></span>
		</a>
	</div>

	<div class="filter_content report_load_advncd_options">
		<div class="advanced_list">
			here load list
		</div>
	</div>
</div>


<?php
}

//ip+
//user name
//window height
//window width
//user message
//опросник который собирает данные и отправляет на сервер
function get_theme_quiz_tpl() {
?>	
	<div class="modal quiz_modal">
		<div class="quiz_modal_wrapper flex-cntr">
			<div class="quiz_modal_content flex-cntr">
				<div class="modal_preloder"></div>
				<div class="quiz_form_wrp flex-cntr">
					<div class="quiz_cont">
						<div class="quiz_header">Proqramın yeni dizayn xoşunuza gəlirmi?</div>
						<div class="quiz_form">
							<div class="quiz_row">
								<div class="quiz_option_list">
									<div class="quiz_rate">
										<a href="javascript:void(0)" class="quiz_btn_style ls_radio quiz_choice">Hə</a>
									</div>
									<div class="quiz_rate">
										<a href="javascript:void(0)" class="quiz_btn_style ls_radio quiz_choice">Yox</a>
									</div>	
								</div>	
								<div class="hidden_info">
									<input type="hidden" class="window_size" data-width="">
									<input type="hidden" class="user_name" data-uname="<?php echo getUser('get_name'); ?>">
								</div>
							</div>	
							<div class="quiz_rate quiz_area">
								<span class="label quiz_lable">Sizin təklifiniz</span>
								<textarea placeholder="text..." maxlength="200" class="quiz_text_area quiz_text_area_style"></textarea>
							</div>
							<div class="quiz_row quiz_submit_box">
								<a href="javascript:void(0)" class="send_quiz qiuz_submit_btn_style">Göndər</a>
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php 	
}


//табы

function get_tab_list_tpl($conf) {

	$title = $conf['title'];
	$link = $conf['link'];
	$tab_active = $conf['active_link'];
	$modify_class = $conf['modify_class'];
	$icon = $conf['icon_img'];
?>
	<div class="tab_select_box">
		<a href="javascript:void(0)" class="<?php echo $tab_active.' '.$modify_class; ?> tab_select_link flex-cntr" data-tab-open="<?php echo $link; ?>">
			<?php 
			echo $icon;
			echo $title 
			?>
		</a>
	</div>
<?php 
}



//customer list
function get_customer_list($arr) {
	global $dbpdo;
	$image_base = '/img/icon/';
	$search = $arr['default_search_value'];
	$custom_class = $arr['custom_class'];
	$search_field = $arr['search_field'];
	$icon_show = $arr['icon'];
	$icon_src = $arr['icon_name'];
	$link = $arr['data-link'];

	$arr = [];
	$get_customer = $dbpdo->prepare('SELECT * FROM customer WHERE customer_name LIKE :search');
	$get_customer->bindValue('search', "%{$search}%");
	$get_customer->execute();

	while($row = $get_customer->fetch(PDO::FETCH_ASSOC))
		$arr[] = $row;
	foreach ($arr as $row ) {
		$id = $row['customer_id'];
		$name = $row['customer_name'];
?>
	<li class="ls-select-li">
		<a href="javascript:void(0)" 
		   class="choice-option choice-style <?php echo $custom_class; ?>" 
		   id="<?php echo $id; ?>" 
		   value="<?php echo $name; ?>" 
		   data-tab-open="<?php echo $link; ?>">
			<?php if ($icon_show == 'show'): ?>
				<span class="mark filter-mark-icon icon"><img src="<?php echo $image_base.$icon_src; ?>"></span>
			<?php endif; ?>
			<span class="mark filter-name"><?php echo $name; ?></span>
		</a>
	</li>
<?php		
	}
}


//таблица для списка пользователей на странице админ панели

function admin_user_list_tr_tpl($arr) {
	$arr = (object) $arr;
?>
	<tr class="stock-list user-list" id="<?php echo $arr->u_id; ?>">
		<td class="k"><?php echo $arr->u_id; ?></td>
		<td class=""><?php echo $arr->u_name; ?></td>
		<td class=""><?php echo $arr->u_password; // тут сделать кнопку 'показать' ?></td>
		<td class=""><?php echo $arr->u_reg_date; ?></td>
		<td>Показать детальней</td>
		<td class="">
			<a class="user_edit_modal_btn get_user_edit_modal" title="Редактировать" href="javascript:void(0)">
				<span class="mark edit-image-parent">
					<img src="/img/icon/edit-rasxod.png" class="icon_img">
				</span>
				<span class="mark edit-btn-text">Редактировать</span>
			</a>
		</td>
	</tr>


<?php 
}

function get_access_page_list_tpl($var) {
	$var = (object) $var; 
?>
	<li class="filter-check-list-style <?php echo $var->parent_class; ?>">
		<a href="javascript:void(0)" class="<?php echo $var->modify_class; ?> filter-check-style u-access-right" data-accces-value="<?php echo $var->value; ?>" id="<?php echo $var->id; ?>">
			<span class="mark mark-filter-checked-icon"><?php echo $var->icon; ?></span>
			<span class="mark filter-name"><?php echo $var->title; ?></span>
			<span class="mark filter-mark-text"><?php echo $var->text; ?></span>
		</a>	
	</li>	
	
<?php
}




//order admin user edit
//шаблон оформления заказа - телефон
function order_admin_user_edit_tpl($var) {
	$var = (object) $var;
	$user_id = $var->user_id;
	$menu_query = $var->menu_query; 
	$user_name 		= get_user_by_id(array('action' => 'get_name', 'user_id' => $user_id));
	$user_password 	= get_user_by_id(array('action' => 'get_password', 'user_id' => $user_id));
	$user_role 		= get_user_by_id(array('action' => 'get_role', 'user_id' => $user_id));
?>


<div class="delete_stock_module">
	<div class="receipet_success">
		<a><img src="img/icon/print-success.png"></a>
	</div>
	<div class="delete_stock_form">
		<div class="del_modal_hdr">
			<span class="del_modal_h">Silmək istədiyinizə əminsiniz ?</span>
		</div>
		<div class="delete_form">
			<div class="module_delete_btn_link">
				<a href="javascript:void(0)" class="delete_user del_accept_btn_style del_form_btn" id="<?php echo $user_id; ?>"> Sil</a>
				<a href="javascript:void(0)" class="del_cancel module_delete_btn_cancle del_form_btn"> Ləğv et</a>
			</div>
		</div>	
	</div>
</div>

<div class="module_order_wrp ls-custom-scrollbar">
	<ul class="modal_order_form" data-order-id="<?= $user_id; ?>">

		<li class="order_modal_list">
			<span class="module_order_desrioption">Ad: </span>
			<input type="text" class="edit_stock_input edit_user_name" value="<?php echo $user_name; ?>" >
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Parol: </span>
			<input type="text" class="edit_stock_input edit_user_pass" value="<?php echo $user_password; ?>" >
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">Məlumat hüququ</span>
			<ul class=" filter-custom-section-list">
				<li class="filter-check-header">
					<span class="filter-title">Məlumat hüququ</span>
				</li>
				<li>	
					<ul class="filter-check-list ls-custom-scrollbar edit_user_rights_parent" data-access-type="ACCESS_DATA">
						<?php 
							get_user_access_list($var = array(
								'action'	   => 'access_data',
								'data_arr'     => '',
								'user_id'      => $user_id
							));  
						?>
					</ul>
				</li>
			</ul>
		</li>

		<li class="order_modal_list">
			<span class="module_order_desrioption">Məlumat hüququ</span>
			<ul class="filter-custom-section-list">
				<li class="filter-check-header">
					<span class="filter-title">Məlumat hüququ</span>
				</li>
				<ul class="filter-check-list ls-custom-scrollbar edit_user_rights_parent" data-access-type="ACCESS_PAGE">
					<?php 
						get_user_access_list($var = array(
							'action'	   => 'access_page',
							'data_arr'     => $menu_query,
							'user_id'      => $user_id
						));  
					?>
				</ul>
			</ul>
		</li>
		<li class="order_modal_list">
			<span class="module_order_desrioption">Məlumat hüququ</span>
			<div class="flex-basis-50 radio-wrapper" ls-radio-initial="upd_user_role" ls-radio-default="<?= $user_role ?>">
				<div class="radio-title">Vəzifə</div>
				<div class="radio-list">
					<a href="javascript:void(0)" class="radio-button" ls-radio-for="upd_user_role" ls-radio-value="admin">
						<span class="radio-state-mark"></span>
						<span class="radio-icon-mark hide"></span>
						<span class="radio-value">Administrator</span>
					</a>
					<a href="javascript:void(0)" class="radio-button" ls-radio-for="upd_user_role" ls-radio-value="admin_seller">
						<span class="radio-state-mark"></span>
						<span class="radio-icon-mark hide"></span>
						<span class="radio-value">Baş satıcı</span>
					</a>
					<a href="javascript:void(0)" class="radio-button" ls-radio-for="upd_user_role" ls-radio-value="seller">
						<span class="radio-state-mark"></span>
						<span class="radio-icon-mark hide"></span>
						<span class="radio-value">Satıcı</span>
					</a>
				</div>
			</div>			
		</li>
		<script type="text/javascript">
			var $def_radio = $('.radio-wrapper[ls-radio-initial="upd_user_role"]');

			var def_value = $def_radio.attr('ls-radio-default');

			$def_radio.find(`.radio-button[ls-radio-value="${def_value}"]`).addClass('radio-active');
		</script>
		<li class="edit_btn_wrp">
			<a href="javascript:void(0)" class="delete_btn_link btn">Sil</a>
			<a href="javascript:void(0)" class="edit_upd_btn_link btn update_user_info_btn">Saxla</a>
		</li>	
	</ul>

</div>	


<?php	
}

//вывести заголовок таблицы
function render_th($var) {
	/** example 
	*	$var = array(
	*		'modify_class' => 'someClass',
	*		'th_name' 	   => 'th',
	*		'td'		   => ''
	*	); 
	*/

?>
	<th class="<?php echo $var['modify_class']; ?>"><?php echo $var['th_name'] ?></th>
<?php }

//вывести строку таблицы
function render_td($var) {
	// del
	// $td_class 		= '';
	// $link_class 	= '';
	// $td_value	 	= '';
	// $mark_text 		= '';

	//$var - array;
	$td = $var['td'];

 	$td_class 	= $td['td_parent_class']; 
 	$link_class = $td['td_link_class']; 
 	$td_value 	= $td['td_value'];
 	$mark_text 	= $td['td_mark_text'];
 	$data_sort  = $td['data-sort']; 
	
?>
 <td class="<?= $td_class ?>">
 	<a href="javascript:void(0)" class="<?= $link_class ?>" data-sort="<?= $data_sort; ?>"> 
 		<span class="stock_info_text"> <?= $td_value; ?> </span>
 		<span class="mark"> <?= $mark_text; ?> </span>
 	</a>
 </td>

<?php 
}

//передаем в качестве агрумента данные, которые хотим вывести, но сперва проверить.
//данные добавляються в эту функцию: если где то надо вывести данные добавляем в эту функцию
function check_td_access_tpl($arr) {
	global $manat_image,
		   $manat_image_green;

	foreach ($arr as $td => $value) {


		$action = $td;

		switch ($action) {
			case 'th_serial':
					check_access_right(array(
						'th_var' => 'th_serial',
						'function' => 'render_td',
						'modify_class' => '',
						'table_data' => array(
							'td_value' 			=> $value,
							'td_parent_class'	=> 'table_stock table_stock_id_box ',
							'td_link_class' 	=> 'stock_info_link_block res_id',
							'td_mark_text'		=> '',
							'data-sort'			=> ''
						)
					));
				break;

			case 'th_prod_name':
				check_access_right(array(
					'th_var' => 'th_prod_name',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> '',
						'td_link_class' 	=> 'stock_name_box_link_btn get_item_stock_action res_name',
						'td_mark_text'		=> '',
						'data-sort'			=> 'name'
					)
				));
				break;

			case 'th_imei':
				check_access_right(array(
					'th_var' => 'th_imei',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> 'table_stock',
						'td_link_class' 	=> 'stock_info_link_block res_imei',
						'td_mark_text'		=> '',
						'data-sort'			=> 'imei'
					)
				));
				break;

			case 'th_buy_price':
				check_access_right(array(
					'th_var' => 'th_buy_price',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> 'table_stock',
						'td_link_class' 	=> 'stock_info_link_block res_fprice',
						'td_mark_text'		=> $manat_image,
						'data-sort'			=> ''
					)
				));
				break;

			case 'th_sale_price':
				check_access_right(array(
					'th_var' => 'th_sale_price',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> 'table_stock',
						'td_link_class' 	=> 'stock_info_link_block res_sprice',
						'td_mark_text'		=> $manat_image,
						'data-sort'			=> ''
					)
				));
				break;
			case 'th_category':
				check_access_right(array(
					'th_var' => 'th_category',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> '',
						'td_link_class' 	=> 'stock_name_box_link_btn get_item_stock_action res_provider',
						'td_mark_text'		=> '',
						'data-sort'			=> ''
					)
				));
				break;	

			case 'th_provider':
				check_access_right(array(
					'th_var' => 'th_provider',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> '',
						'td_link_class' 	=> 'stock_name_box_link_btn get_item_stock_action res_provider',
						'td_mark_text'		=> '',
						'data-sort'			=> 'provider'
					)
				));
				break;

			case 'th_return':
				check_access_right(array(
					'th_var' => 'th_return',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> '',
						'td_link_class' 	=> 'res_return',
						'td_mark_text'		=> '',
						'data-sort'			=> ''
					)
				));
				break;	

			case 'th_buy_day': 
				check_access_right(array(
					'th_var' => 'th_buy_day',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> '',
						'td_link_class' 	=> 'stock_name_box_link_btn get_item_stock_action res_date',
						'td_mark_text'		=> '',
						'data-sort'			=> ''
					)
				));
				break;	
			case 'th_count':
				check_access_right(array(
					'th_var' => 'th_count',
					'function' => 'render_td',
					'modify_class' => '',
					'table_data' => array(
						'td_value' 			=> $value,
						'td_parent_class'	=> 'table_stock',
						'td_link_class' 	=> 'stock_info_link_block res_count',
						'td_mark_text'		=> '',
						'data-sort'			=> ''
					)
				));																
				break;

				case 'th_day_sale':
					check_access_right(array(
						'th_var' => 'th_day_sale',
						'function' => 'render_td',
						'modify_class' => '',
						'table_data' => array(
							'td_value' 			=> $value,
							'td_parent_class'	=> '',
							'td_link_class' 	=> 'stock_name_box_link_btn get_item_stock_action res_sale_date',
							'td_mark_text'		=> '',
							'data-sort'			=> 'date'
						)
					));						
					break;

				case 'th_report_note':
					check_access_right(array(
						'th_var' => 'th_report_note',
						'function' => 'render_td',
						'modify_class' => '',
						'table_data' => array(
							'td_value' 			=> $value,
							'td_parent_class'	=> 'table_stock',
							'td_link_class' 	=> 'stock_info_link_block res_report_note',
							'td_mark_text'		=> '',
							'data-sort'			=> ''
						)
					));						 
				break;	

				case 'th_profit':
					check_access_right(array(
						'th_var' => 'th_profit',
						'function' => 'render_td',
						'modify_class' => '',
						'table_data' => array(
							'td_value' 			=> $value,
							'td_parent_class'	=> 'table_stock',
							'td_link_class' 	=> 'stock_info_link_block res_profit font-green',
							'td_mark_text'		=>  $manat_image_green,
							'data-sort'			=> ''
						)
					));						
				break;
		}

	}
}




//передаем в качестве агрумента данные, которые хотим вывести, но сперва проверить.
//данные добавляються в эту функцию: если где то надо вывести данные добавляем в эту функцию
function check_th_access_tpl($arr) {
	global $manat_image;
	foreach ($arr as $td => $value) {


		$action = $td;
		$modify_class = $value;

		switch ($action) {
			case 'th_serial':
					check_access_right(array(
						'th_var' => 'th_serial',
						'function' => 'render_th',
						'modify_class' => 'th_w40'
					));			
				break;

			case 'th_prod_name':
					check_access_right(array(
						'th_var' => 'th_prod_name',
						'function' => 'render_th',
						'modify_class' => 'th_w250'
					));			
				break;

			case 'th_imei':
					check_access_right(array(
						'th_var' => 'th_imei',
						'function' => 'render_th',
						'modify_class' => 'th_w250'
					));			
				break;

			case 'th_buy_price':
					check_access_right(array(
						'th_var' => 'th_buy_price',
						'function' => 'render_th',
						'modify_class' => 'th_w80'
					));			
				break;

			case 'th_sale_price':
					check_access_right(array(
						'th_var' => 'th_sale_price',
						'function' => 'render_th',
						'modify_class' => 'th_w80'
					));			
				break;
			case 'th_category':
					check_access_right(array(
						'th_var' => 'th_category',
						'function' => 'render_th',
						'modify_class' => 'th_w200' 
					));				
				break;	

			case 'th_provider':
					check_access_right(array(
						'th_var' => 'th_provider',
						'function' => 'render_th',
						'modify_class' => 'th_w200'
					));			
				break;

			case 'th_return':
					check_access_right(array(
						'th_var' => 'th_return',
						'function' => 'render_th',
						'modify_class' => 'th_w40'
					));			
				break;	

			case 'th_buy_day':
					check_access_right(array(
						'th_var' => 'th_buy_day',
						'function' => 'render_th',
						'modify_class' => 'th_w120'
					));				 
				break;	
			case 'th_count':
					check_access_right(array(
						'th_var' => 'th_count',
						'function' => 'render_th',
						'modify_class' => 'th_w80'
					));																	
				break;

				case 'th_day_sale':
					check_access_right(array(
						'th_var' => 'th_day_sale',
						'function' => 'render_th',
						'modify_class' => 'th_w120'
					));						
					break;

				case 'th_report_note':
					check_access_right(array(
						'th_var' => 'th_report_note',
						'function' => 'render_th',
						'modify_class' => 'th_w120'
					));				 
					break;	

				case 'th_profit':
					check_access_right(array(
						'th_var' => 'th_profit',
						'function' => 'render_th',
						'modify_class' => 'th_w80'
					));					
					break;				
		}

	}
}
