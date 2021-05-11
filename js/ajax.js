

//меню на главной странице
$(document).ready(function(){
	// $('body').on('click', '.get_page_action', function(){
	// 	//смотри в option.php
	// 	//получаем ссылку на страницу
	// 	var $main = $('.main');
	// 	var page = $(this).attr('data-page');
	// 	var tab = $(this).attr('data-tab');
	// 	var page_url = $(this).attr('data-url');
	// 	$.ajax({
	// 		type: 'POST',
	// 		url:  '/page/route.php',
	// 		data: {
	// 			'page': page,
	// 			'tab': tab,
	// 			'url': page_url,
	// 			'data-page-route' : $(this).attr('data-page-route')
	// 		},
	// 		success: (data) => {
	// 			var siderbar_width = $('.sidebar_menu_wrp').width();
	// 			$('.container').css('margin-left', siderbar_width);
	// 			//проверка данных на json
	// 			if(IsJsonString(data)) {
	// 				var par = $.parseJSON(data);
	// 				$main.html(par['error']);
	// 			} else {
	// 				$main.html(data);
	// 			}
		
	// 			$main.addClass('main__active');
	// 			$('.main-option-service').attr("style", "display: block; width: 200px;height: 100%;");
	// 			$('.options-list').attr("style", "display: block;width: 200px;height: auto;");
	// 			//показать боковое меню
	// 			show_sidebar_menu('/page/terminal/terminal.php', '');

	// 			// register_observer();
	// 			//сообщене если талица пуста
	// 			add_table_empty_block();

	// 			//загрузить карточки статистики
	// 			get_stats_info();
	// 			console.log('select page');

	// 		}			
	// 	});
	// });

//переключения вкладок 
// $('body').on('click', '.tab_select_link', function(){

// 	//очищаем от паддинга 
// 	//$('.main').css('padding-right', '0');

// 	//класс активная кнопка
// 	var tab_btn_activ = 'tab_activ';			   			 		

// 	//фон активной кнопки
// 	var $tab_bcg = $(".tab_selected_bcg");

// 	//находим все кнопки переключения вклдаки 
// 	$('.tab_select_link').each(function(){							
// 		//удаляем активный класс у кнопки 	
// 		$('.tab_select_link').removeClass(tab_btn_activ)			
// 	});
// 	//получаем кнопку на которую кликнули
// 	var $item = $( this ); 
	
// 	//получаем ширину кнопки
// 	var itemWidth = $item.css('width');
// 	//получаем позицию кнопки 								
//     var offsest = $item.position();		

//     //добавляем к кнопке класс .tab_activ что бы сделать ее активным				
// 	$(this).addClass(tab_btn_activ); 

// 	//перемещаем фон активной вкладки под позицию кнопки на которую кликнули		
// 	$tab_bcg.css({
// 		'left' : offsest.left,
// 		'width' : itemWidth
// 	});  	

// 	var page = $(this).attr('data-page');
// 	var tab = $(this).attr('data-tab');
// 	var page_url = $(this).attr('data-url');
// 	get_preloader('show');
// 		$.ajax({
// 			url:  '/page/route.php',
// 			type: 'POST',
// 			data: {				
// 				'page': page,
// 				'tab': tab,
// 				'url': page_url,
// 			},
// 			success: (data) => {
// 				$main = $('.main.main__active');

// 				$main.find('.terminal_main').remove();
// 				$main.append(data);
// 				get_preloader('hide');

// 				//сообщене если талица пуста
// 				add_table_empty_block();

// 				get_stats_info();
// 				console.log('select tab');

// 			}
// 		});
	 			
// });




});

// //левое бокове меню
// function show_sidebar_menu(dataLink, param) {
// 	$('.sidebar_menu_wrp').show();

// 	$('.sidebar_link').each(function(item){
// 		$(this).removeClass('sidebar_active');

// 		item = $(this).data('link');

// 	});
// 	$('.sidebar_link[data-link="'+dataLink+'"]').addClass('sidebar_active');


// 	if( $('.tab_activ').length ) {
// 		var $active_sort_tab = $('.tab_select_box');

// 		 $active_sort_tab.find('.tab_activ').each(function(){
// 			 $sort_tab_data = $(this).closest('.tab_select_box').html(); 
// 			 $sort_tab_data = $(this).closest('.tab_select_box').remove();
// 		});

// 		$('.tab_change_box').prepend($sort_tab_data);

// 		var $active_tab = $('.tab_activ');
// 		//настроить ширину для меню навигации вкладок
// 		var active_tab_width  = $active_tab.outerWidth();

// 		var offset = $active_tab.position();

// 		$('.tab_selected_bcg').css({
// 			width : active_tab_width,
// 			left : offset.left
// 		});
// 	}
// }
//поиск товаров по клику на фильтр 
$('body').on('click', '.get_item_by_filter', function(){
	//делаем поиск по значению 
	// var search_item_value = $(this).find('.stock-info-text').text();
	var search_item_value = $(this).data('sort-value');

	//for report sort data 
	var sort_data = $(this).data('sort');
	search_item_stock(search_item_value, sort_data);
});



// //поиск товаров по инпуту
// $('body').on('keyup', '.search_stock_input_action', function() {

// 	var $this = $(this);
// 	var $delay = 500;

// 	clearTimeout($this.data('timer'));

// 	$this.data('timer', setTimeout(function(){
// 		var search_item_value = $this.val();
// 		var search_item_value = search_item_value.replace(/\s+/g,' ').trim();

// 		if(search_item_value.length>2) {
// 			 search_item_stock(search_item_value);
// 		}
// 	}, $delay));	
// });

//autocompelete для инпута

$('body').on('keyup', '.stock_auto_compelete', function(){
	$('.auto-cmplt-result').html('<div class="auto-cmplt-preloader"><img src="/img/icon/load.gif"></div>');
	var $this = $(this);
	var $delay = 450;

	clearTimeout($this.data('timer'));

	$this.data('timer', setTimeout(function(){
		var $parent = $this.closest('.auto-cmplt-parent').find('.auto-cmplt-result');
		var search_data = $this.val();
		var data_name = $this.attr('data-name');
		var search_data = search_data.trim();
		var search_filter = '';
		var autocmplt_type = $parent.attr('data-auto-cmplt-type');
		var $table = $('.stock_list_tbody');

		var page = $table.attr('data-stock-page');
		var type = $table.attr('data-stock-type'); 

		if( $('.auto-cmplt-parent').hasClass('search_filter') ) {
			search_filter = 'show';
		}

		if(search_data.length > 1) {

			$.ajax({
				type: 'POST',
				url: '/core/action/autocomplete.php',
				data: {
					value: search_data,
					action: data_name,
					search_filter: search_filter,
					page: page,
					type: type,
					autocmplt_type: autocmplt_type
				},
				success: (data) => {
					if(data.length <= 0) {
						$parent.html('Heç nə tapılmadı');
					} else {
						$parent.html(data);
					}
				}
			});		

		}		
	}, $delay));
});


//поиск по дате в отчете
$(document).ready(function(){
	$('body').on('change', '.report_options_list', function(){
		let report_date_value = $(this).val();
		//for report sort data 
		var sort_data = $(this).attr('data-sort');

		$('.stats-date').data('cur-date', report_date_value);

		search_item_stock(report_date_value,sort_data);
		get_stats_info();
	});
});





//поиск товаров 
function search_item_stock(search_item_value,sort_data) {
	let $search_main_table = $('.stock_list_tbody');
	//тут мы получаем тип таблицы (terminal, stock, report и тд)
	let search_from 		= $search_main_table.attr("data-stock-page");	
	let search_product_cat  = $search_main_table.attr("data-stock-type");

	get_preloader('show');

	$.ajax({
		type: 'POST',
		url: '/core/action/search.php',
		data: {
			search_item_value	: search_item_value, 
			page				: search_from, 
			type			    : search_product_cat,
			sort_data 			: sort_data
		},
		dataType: 'json',
		success: (data) => {
			//выводим в талицу данные
			if(data.table) {
				$search_main_table.html(data.table);	
			}
			if(data.total) {
				update_tfoot_reuslt(data.total);
			}
			get_preloader('hide');
			console.log('hello world');

		}			
	});
}


//получаем данные товара в модальном окне для оформления зазказа
$(document).ready(function(){
	$('body').on('click', '.table_stock', function(){
		$modal = $('.modal_view_stock_order');

		add_preloader($modal);
		
		//убираем активированный продукт
		remove_selected_product();

		$('.get_order_action').removeClass('click');

		//получаем строку продукта
		var $product_list = $(this).closest('.stock-list');			

		//получаем id проддукта от родительсокого эелемента
		var product_id = $product_list.attr("id");		
		//report_order_id
		var order_id = $product_list.find('.get_report_order_id').attr('data-sort-value');
		//получем вкладку таблицы (terminal, stock, report)
		var page = get_page_param("data-stock-page");
		
		//получаем категорию товара (телефоны, аксессуары и тд)
		var type = get_page_param("data-stock-type");
		addLeftPaddingModal();
		// console.log({product_id, page, type});
		$.ajax({
			type: 'POST',
			url: '/core/modal_action/order.php',
			data:{
				product_id : product_id,
				order_id: order_id, 
				type  : type, 
				page  : page
			},
			success: (data) => {
				$($modal).html('');
				$product_list.addClass("o_product_selected");
				$($modal).html(data);
			}			

		});

	});
	
});


//оформление заказа
$(document).ready(function(){
	$('body').on('click', '.get_order_action', function(){

	if(!$(this).hasClass('click')){

		$(this).addClass('click');

		//id товара
		var order_product_id = $('.modal_order_form').data("order-id");

		var $order_count_val = $('.order_count_action');

		//количество товра
		var order_product_count = $order_count_val.val();

		//цена продажи
		var order_last_price = $('.order_price_stock').val();

		var order_note = $('.order_note_action').val();

		$.ajax({
			type: 'POST',
			url: '/core/action/add_order.php',

			data: {
				product_id   : order_product_id,	
				product_cont : order_product_count,	
				order_price  : order_last_price,
				order_note   : order_note	
			},
			dataType: 'json',
			success: (data) => {
				//ловим ошибку
				var order_error =  data['error_notify'];

				//если заказ выполнен успешно 
				var order_success = data['order_success'];

				//изменить количество товра если осталось
				var updated_count = +data['updated_count'];

				//скрыть товар если количество 0 
				var upd_hide_stocks = +data['upd_hide_stock'];

				//если есть ошибка
				if(order_error) {
					 show_error_modal(order_error);
				}

				//если выполнен успешно
				if(order_success.length !== 0) {
					//товар в таблце терминала   
					var $get_stock_list = $('.stock-list#' + order_product_id);
					//сколько осталось
					var stock_left_count = updated_count;

					//выводим модально окно о успешной операции и пердаем id товара
					$('.modal_view_stock_order').append(data['successs_modal']);

					//если осталось в базе выводим сколько осталось 
					if(updated_count > 0) {
						$get_stock_list.find('.res_count').html(stock_left_count);
					} else {
						$get_stock_list.fadeOut();
					}	
				}
				
				console.log(order_success);

			}			

		});
		} 
	});
});


//форма добавления товра в базу
$('body').on('click', '.add_stock_submit', function(){
	if(!$(this).hasClass('clicks')){

		$(this).addClass('click');

		//таблица
		var $table_stock_list 	= $('.stock_list_tbody');
		
		//результат таблицы

		var $get_prod_name		= $('.add_stock_name_action');
		var $get_prod_imei		= $('.add_stock_imei_action');
		var $get_prodt_count   	= $('.add_stock_count');
		var $get_prod_provider 	= $('.add_stock_provider_action');
		var $get_prod_fprice  	= $('.add_stock_first_price_action');
		var $get_prdot_sprice 	= $('.add_stock_second_price_action');

	
		//получаем категорию сервиса
		var get_page	= $table_stock_list.attr("data-stock-page");
		//получем категорию товара
		var get_type	= $table_stock_list.attr("data-stock-type");
	
		//данные с формы
		//имя товара
		var product_name		= $get_prod_name.val();
		//imei товара
		var product_imei		= $get_prod_imei.val();
		//количество товара
		var count   	= $get_prodt_count.val();
		//поставщик/категория (если акссесуар)
		var product_provider 	= $get_prod_provider.val();
		//себестоимость товара
		var product_first_price	= $get_prod_fprice.val();
		//стоимость товара
		var product_price 		= $get_prdot_sprice.val();

		//filter
		//color
		var filter_list = [];
		$('.filter_btn.active-dropdown').each(function(){
			id = $(this).attr('id');
			filter_list.push(id);
		});

		$.ajax({
			type: 'POST',
			url: 'core/action/add_stock.php',

			dataType: 'json',
			data: {
				get_page	 		: get_page,
				get_type		 	: get_type,

				name				: product_name,
				imei     			: product_imei,
				count    			: count,
				second_price    	: product_price,
				provider 			: product_provider,
				first_price 		: product_first_price,

				filter_list			: filter_list

			},
			success: (data) => {
				//собщение если товар добавлен
				var product = data['product'];
				//сообщение модального окна
				var success = data['ok'];
				//вывод ошибки
				var error   = data['error'];
				//если есть ошибка
				if(error) {
					//вызывем модалку ошибки 
					show_error_modal(error);							
				}

				if(data['error_not_unique']) {
					clearInput();
					$('.main.main__active').prepend(data['error_not_unique']);
				}
				
				//если товар добавлен успешно
				if(success) {
					//добавляем товар в начало таблицы
					$table_stock_list.prepend(product);
					if(data['total']) {
						update_tfoot_reuslt(data['total']);
						console.log(data['total']);
					}
					//вызывем модалку success 
					show_success_modal(success);
					
					reset_all_filter_add();

					clearInput();
				}
	
				//очищаем input
				function clearInput() {
					$get_prod_name.val('');	
					$get_prod_imei.val('');	
					$get_prodt_count.not('.not_resetbl').val(''); 
					$get_prod_provider.val('');
					$get_prod_fprice .val('');	
					$get_prdot_sprice.val('');					
				}
				

			}			
		});
		
	} else {
		//если кнопка не активна и обезательные поля пустые
		//вызываем функцию ошибки
		var error_text = 'Bütün sahələri doldurun!';
		show_error_modal(error_text);
	}
});



//редактировать товар
$('body').on('click', '.edit_stock_action', function(){

	//получаем таблицу
	var $get_table 			= $('.stock_list_tbody');
	//форма модального окна
	var $modal_form 		= $('.modal_order_form');

	//получем id товара
	var upd_product_id		= $modal_form.attr("data-order-id");
	var prod_category  		= $get_table.attr("data-stock-type");
	//инпуты - 
	//имя продукта
	var product_name  		= 	$('.edit_sotck_name_input').val();
	//imei продукта
	var product_imei  		=   $('.edit_stock_imei_input').val();
	//provider
	var product_provider	=	$('.edit_stock_provider_input').val();
	//себе стоимость
	var product_fprice 		=	$('.edit_sotck_fprice_input').val();
	//цена прожади 
	var product_sprice      =   $('.edit_stock_sprice_input').val();
	//количество товара
	var product_count       =   $('.upd_product_count').val();

	//прибавляем к количеству товара
	var prdocut_count_plus  =   $('.edit_count_plus').val();

	//отнимаем количество товра
	var prdocut_count_minus  =  $('.edit_count_minus').val();

	var filter_list = [];
	$('.filter_btn.active-dropdown').each(function(){
		id = $(this).attr('id');
		filter_list.push(id);
	});

	console.log(filter_list);

	$.ajax({
		type: 'POST',
		url: 'core/action/update_product.php',
		data:{	
			upd_product_id		: upd_product_id,
			prod_category		: prod_category,
			product_name		: product_name,
			product_imei		: product_imei,
			product_provider 	: product_provider,
			product_fprice 		: product_fprice,
			product_sprice 		: product_sprice,
			product_count 		: product_count,
			prdocut_count_plus  : prdocut_count_plus,
			prdocut_count_minus : prdocut_count_minus,
			
			// filter_list 		: filter_list			
		},
		dataType: "json",
		success: (data) => {
			var error 	= data['error'];
			var success = data['success'];
			if(error) {
				show_error_modal(error);
			}

			if(success) {
				//обн.имя
				// var upd_
				// var upd_name 	= data['upd_name'];
				// var upd_imei 	= data['upd_imei'];
				// var upd_fprice 	= data['upd_fprice'];
				// var upd_sprice 	= data['upd_sprice'];
				// var upd_prov   	= data['upd_provider'];
				// var upd_count 	= data['upd_count'];

				//показываем сообщение о успехе
				
				show_success_modal(success);
				//скрываем сообщение о успехе спустя 4 сек

				var res_name 		=	data['upd_name']; 
				var res_imei 		= 	data['upd_imei'];
				var res_fprice 		= 	data['upd_fprice'];
				var res_sprice 		= 	data['upd_sprice'];
				var res_provider 	= 	data['upd_provider'];
				var res_count 		= 	data['upd_count'];
				//обновляем имя 

				$parent = $(`.stock-list#${upd_product_id}`);
				

				$parent.find('.res_name>.stock-info-text').html(res_name);
				$parent.find('.res_imei>.stock-info-text').html(res_imei);
				$parent.find('.res_fprice>.stock-info-text').html(res_fprice);
				$parent.find('.res_sprice>.stock-info-text').html(res_sprice);
				$parent.find('.res_provider>.stock-info-text').html(res_provider);
				$parent.find('.res_count>.stock-info-text').html(res_count);
			}
		}			

	});

});



//возврат товара
$(document).ready(function(){
	$('body').on('click', '.get_return_accept_btn', function(){
		var $return_input_value = $('.return_input_action');

		var product_count = $return_input_value.val();
		var product_report_id = $return_input_value.attr('data-report-id');
		var product_id = $return_input_value.attr('data-prod-id');


		$.ajax({
			type: 'POST',
			url: 'core/action/return_report.php',
			dataType: 'json',
			data: {
				product_count : product_count,
				product_report_id : product_report_id,
				product_id : product_id
			},
			success: (data) => {

				if(data['ok']) {
					$('.stock-list#' + product_id).fadeOut();
					$('.receipet_success').fadeIn().css('display', 'flex');
				}

				if(data['success']) {
					$('.receipet_success').fadeIn().css('display', 'flex');
				}
			}
		});
	});
});


//удаления товара
$(document).ready(function(){
	$('body').on('click', '.module_delete_btn', function(){
		var delete_products = $('.modal_order_form').attr("data-order-id");

		$.ajax({
			type: 'POST',
			url: 'core/action/delete_products.php',

			data: {
				delete_products: delete_products
			},
			dataType: 'json',
			success: (data) => {
				if(data['ok']) {
					$('.receipet_success').fadeIn().css('display', 'flex');
					$('.stock_list_tbody').find(`.stock-list#${delete_products}`).remove();
					//сообщение если все ок
					show_success_modal(data['ok']);				
				}
				if(data['error']) {
					show_error_modal(data['error']);
				}
			}
		});

	});
});

//добавить профит в отчете (дикий костыль переделать!!!!)
$('body').on('click', '.add_profit_action', function(){

	var profit_name = $('.profit_name').val();
	var profit_value = $('.profit_money').val();

	$.ajax({
		type: 'POST',
		url: 'core/action/add_order.php',
		data: {
			profit_name: profit_name,
			profit_value : profit_value
		},
		dataType: 'json',
		success: (data) => {
			if(data['ok']) {
				$('.add_profit_modal').fadeOut();
					//сообщение если все ок
				show_success_modal(data['ok']);					
			}
		}
	});
});



//модальное окно ошибки 
function show_error_modal(eror_text) {
	var $fail_notify        = $('.fail_notify');

	var error  = eror_text;
	//показываем сообщение о ошибки
	$fail_notify.slideDown(400, 'swing').html(error);
	//скрываем сообщение о ошибки спустя 4 сек
	setTimeout(function(){
		$fail_notify.slideUp(400, 'swing');
	}, 4000);

}


//модальное окно успеха 
function show_success_modal(success_text) {
	var $clearTime = '1500';

	var $success_notify  = $('.success_notify');

	var success  = success_text;
	//показываем сообщение о ошибки
	$success_notify.slideDown(400, 'swing').html(success);
	//скрываем сообщение о ошибки спустя 4 сек
	setTimeout(function(){
		$success_notify.slideUp(400, 'swing');
	}, $clearTime);

}


//preloader show/hide 
function get_preloader(action) {
	if(action === 'show') {
		$('.preloader_wrapper').fadeIn('fast').css('display', 'flex');
	} 
	if(action === 'hide') {
		$('.preloader_wrapper').fadeOut('fast');
	}
	
}


//добавить заметку
$('body').on('click', '.add_note_submit', function(){
	var $note_name_input 	= 	$('.add_note_name_action');
	var $note_descrp_input 	= 	$('.add_note_descript');

	var note_name 		=	$note_name_input.val();
	var note_descrpt 	=	$note_descrp_input.val();
	var note_type 		=	$('.note_action_type').attr('data-type');
	const table_param   = 	['.note_order_list', '[data-stock-page=', note_type + ']'];

	var $note_table     =   $(table_param.join(''));

	$.ajax({
		type: 'POST',
		url: 'core/action/note_action/add_note.php',
		data: {
			note_name 		: note_name,
			note_descrpt 	: note_descrpt,
			note_type 		: note_type
		},
		dataType: 'json',
		success: (data) => {
			var success = data['success'];
			var error   = data['error'];
			var table   = data['table'];
			if(success) {
				show_success_modal(success);
				$note_table.prepend(table);		
				$note_name_input.val('');
				$note_descrp_input.val('');
			}

			if(error) {
				show_error_modal(error);
			}
		}		
	});
});

//редактировать заметку
$('body').on('click', '.note_table' ,function(){
	var $this = $(this);
	var get_note_id  = $this.attr('id');
	$('.note_table').removeClass("o_product_selected");

	$.ajax({
		type: 'POST',
		url:  'core/modal_action/service_order.php',
		data: {
			get_note_id : get_note_id
		}, 
		success: (data) => {
		 addLeftPaddingModal();
		 $this.addClass("o_product_selected");
		 $('.modal_view_stock_order').html(data);
		}
	});

});


//редактирование заметки
$('body').on('click', '.save_edit_note', function(){
	var get_note_id			= $('.modal_order_form').data('order-id');
	var get_upd_name 		= $('.note_name_upd_actinon').val();
	var get_upd_dsecrpt 	= $('.note_descrpt_upd_action').val();

	$.ajax({
		type: 'POST',
		url:  'core/action/note_action/update_note.php',
		data: {
			update_note 	:  get_note_id,
			get_upd_name  	:  get_upd_name,
			get_upd_dsecrpt :  get_upd_dsecrpt
		},
		dataType: 'JSON',
		success: (data) => {
			var success = data['success'];
			if(success) {
				show_success_modal(success);
				$('.note_table#'+get_note_id).find('.note_name_a').text(get_upd_name);
				$('.note_table#'+get_note_id).find('.note_descrpt_a').text(get_upd_dsecrpt);				
			}
		}
	});
});

//удалить заметки\оповищения
$('body').on('click', '.delete_note_a', function(){

	var note_delete_id  = $('.modal_order_form').data('order-id');

	$.ajax({
		type: 'POST',
		url: '/core/action/note_action/delete_note.php',
		data: {
			note_delete_id:note_delete_id
		},
		dataType: 'json',
		success: (data) => {
			$('.note_table#'+note_delete_id).remove();
			show_success_modal('OK');
		}
	});
});









//добавить расход
$('body').on('click', '.add_rasxod_submit', function(){
	var $rasxod_value_input 	= 	$('.add_rasxod_value_a');
	var $raxod_descrp_input 	= 	$('.add_rasxod_descript_a');
	var rasxod_name 			=	$rasxod_value_input.val();
	var rasxod_descrpt 			=	$raxod_descrp_input.val();
	$.ajax({
		type: 'POST',
		url: 'core/action/rasxod_action/add_rasxod.php',
		data: {
			add_rasxod 		: rasxod_name,
			rasxod_descript : rasxod_descrpt
		},
		dataType: 'json',
		success: (data) => {
			var success = data['success'];
			var error 	= data['error'];
			var table 	= data['table'];
			if(success) {
				show_success_modal(success);
				$('.rasxod_order_list').prepend(table);
				$rasxod_value_input.val('');
				$raxod_descrp_input.val('');				
			}

			if(error) {
				show_error_modal(error);
			}
		}		
	});
});

//модально окно редактирование расхода
$('body').on('click', '.rasxod_table', function(){
	var $this = $(this);
	var $parent_query = $this.closest('.rasxod_list_tr');

	$('.rasxod_list_tr').removeClass('o_product_selected');

	var get_rasxod_id 	= 	$parent_query.attr('id');

	$.ajax({
		type: 'POST',
		url: 'core/modal_action/service_order.php',
		data: {
			get_rasxod_id 	: get_rasxod_id
		},
		success: (data) => {
			 addLeftPaddingModal();
			 $parent_query.addClass("o_product_selected");
			 $('.modal_view_stock_order').html(data);
		}		
	});
});

//удалить расход
$('body').on('click', '.delete_rasxod_action', function(){
	var delete_rasxod = $('.modal_order_form').attr('data-order-id');
	$.ajax({
		type: 'POST',	
		url: 'core/action/rasxod_action/delete_rasxod.php',
		data: {
			delete_rasxod : delete_rasxod
		},
		dataType: 'json',
		success: (data) => {
			$('.rasxod_list_tr#'+delete_rasxod).remove();
			show_success_modal('OK');

			$('.module_fix_right_side').hide('slow');
		}		
	});	

});


//редактирование расход
$('body').on('click', '.save_edit_rasxod', function(){
	var get_note_id			= $('.modal_order_form').data('order-id');
	var get_upd_name 		= $('.note_name_upd_actinon').val();
	var get_upd_dsecrpt 	= $('.note_descrpt_upd_action').val();

	$.ajax({
		type: 'POST',
		url:  'core/action/rasxod_action/update_rasxod.php',
		data: {
			update_rasxod 	:  get_note_id,
			get_upd_name  	:  get_upd_name,
			get_upd_dsecrpt :  get_upd_dsecrpt
		},
		dataType: 'json',
		success: (data) => {
			var success = data['success'];
			if(success) {
				show_success_modal(success);
				$('.rasxod_list_tr#'+get_note_id).find('.rasxod_value_a').text(get_upd_name);
				$('.rasxod_list_tr#'+get_note_id).find('.rasxod_descrpt_a').text(get_upd_dsecrpt);				
			}
		}
	});
});


//фильтрация товара
$('body').on('click', '.filter-check', function(){
	//выбранный фильтер 
	var $this = $(this);
	//переключаем состяние на активный
	$this.toggleClass('filter-active');

	//таблица куда выводить таблицу
	let $table_body = $('.stock_list_tbody');

	//показывать количестов выбранных фильтров 
	display_active_filter_count();

	//показывать количестов фильтров выбранной категории
	display_active_this_filter_count($this);

	//массив в котороый будем заночить выбранные фильтры
	const filter_list = [];

	var data_page = get_page_param('data-stock-page');
	var data_type = get_page_param('data-stock-type');

	//заполняем массив активными фильтрами
	$('.filter-active').each(function(){
		let filter_id = $(this).attr('id');
		let filter_type = $(this).attr('filter-type');
		filter_list.push({filter_id, filter_type});
	});
	
	$.ajax({
		type: 'POST',
		url:  '/core/action/get_filter_stock.php',
		data: {
			id : filter_list,
			page: data_page,
			type: data_type
		},
		dataType: 'JSON',
		success: (data) => { 
			//выводим в талицу данные
			if(data.table) {
				$table_body.html(data.table);	
			}
			if(data.total) {
				update_tfoot_reuslt(data.total);
			}
			get_preloader('hide');
		}				
	});

	if(filter_list.length <= 0 ) {
		clear_page();
	}	
});


//загрузить доп функции для окна на спаемцу репрпи
$('body').on('click', '.load_advanced_report', function(){
	var $table = $('.stock_list_tbody');
	var prod_cat = $table.attr('data-stock-type');
	var $advanced = $('.advanced_list');
	$advanced.html('<div class="auto-cmplt-preloader"><img src="/img/icon/load.gif"></div>');

	$.ajax({
		type: 'POST',
		url: 'core/action/load_advanced_option',
		data: {prod_cat: prod_cat},
		success: (data) => {
			$advanced.html(data);
		}
	});
});


//опросник
$(document).ready(function(){
	var screen_width = $(window).width();
	var screen_height = $(window).height();
	$('.window_size')
	.attr('data-width', screen_width)
	.attr('data-height', screen_height);

	$('body').on('click', '.send_quiz', function(){
		var $window_size = $('.window_size');
		var username = $('.user_name').attr('data-uname');
		var width = $window_size.attr('data-width');
		var height = $window_size.attr('data-height');
		var msg = $('.quiz_text_area').val();
		var quiz_answer = $('.ls_radio.ls_radio_activ').text();

		var $quiz_modal_content = $('.quiz_modal_content>.modal_preloder'); 
		if(quiz_answer.length > 0) {
			add_preloader($quiz_modal_content);
			$quiz_modal_content.css({
				'display': 'block',
				'background-color' : 'rgb(24 24 24 / .8)'
			});


			$.ajax({
				url: '/core/action/send_quiz_api.php',
				type: 'POST',
				data: {
					username: username,
					width: width,
					height: height,
					msg: msg,
					quiz_answer: quiz_answer
				},
				success: (data) => {
					$('.quiz_modal').hide();
					show_success_modal('Göndərildi. Təşəkkür edirik!');
				}
			});
		} else {
			show_error_modal('Выбирите один вариант');
		}

	});
});



$('body').on('click', '.get_customer', function(){
	var customer_id = $(this).attr('id');
	var link = $(this).data('tab-open');
	$('.table_wrapper').removeClass('hide');

	$result_wrapper = $('.debt-table-list');

	$.ajax({
		type: 'POST',
		url: link,
		data: {
			customer_id: customer_id
		},
		dataType: 'json',
		success: (data) => {
			$result_wrapper.html(data['name']);
		}

	});

});


/******update end******/






// let handleKeyPress = (e) => {


// 	var $this = $(this);
// 	var $delay = 500;

// 	clearTimeout($this.data('timer'));
// 	if( !$('.barcode-readers').is(':focus') ) {
// 		$('.barcode-reader').focus();
// 		$this.data('timer', setTimeout(function(){
// 			var id = $('.barcode-reader').val();



// 			$.ajax({
// 				url: '/core/action/get_barcode_product.php',
// 				type: 'POST',
// 				data: {
// 					id: id
// 				},
// 				success: (data) => {
// 					$('.list').append(data);
// 					// $('.barcode-reader').val('');
// 				}
// 			});


// 		}, $delay));
// 	}

// }

// $(document).bind('keydown', handleKeyPress);

//barcode start
$(function(){
$('body').on('click', '.load_barcode_modal', function(){
	$('.list').fadeIn();
    $(document).pos();
});
	var prod = false;
	const totla_product = [];
	function check_is_table(id, el, data) {
		var $ele = $('.checkout_product#'+id);

		if($ele.length > 0) {
			// alert('Такой продукт есть на странице - добавить к нему +1' + ele);
			add_count(id);
		} else {
			append_product(el);
			push_data(data);
			// alert('Такого продукта нет - добавить сам продукт');
		}
	}


	function append_product(el) {
		$('.bc_product_table').append(el);
	}

	function add_count(id) {
		$prod_count = $('.checkout_product#'+id).find('.checkout_prod_count');
		var count = $prod_count.val();

		count++;
		$prod_count.val(count); 
	}

	function push_data(data) {
		var stock_id = data['param']['stock_id'];
		var stock_name = data['param']['stock_name'];
		var stock_price = data['param']['stock_second_price'];
		var stock_count = data['param']['stock_count'];

		myData = {
			id: stock_id,
			name: stock_name,
			price: stock_price,
			count: stock_count
		}

		totla_product.push(myData);


	}

    $(document).on('scan.pos.barcode', function(event){
        var barcode = event.code;
			$.ajax({
				url: '/core/action/get_barcode_product.php',
				type: 'POST',
				dataType: 'json',
				data: {
					id: barcode
				},
				success: (data) => {
					var id = data['param']['stock_id'];
					var el = data['table'];
					check_is_table(id, el, data);		
				}
			});         
    });


    $('body').on('click', '.send_arry', function(){
			var myJSON = JSON.stringify(totla_product);
			$.ajax({
				url: '/core/action/cart_checkot.php',
				type: 'POST',
				data: {
					res: myJSON
				},
				success: (data) => {
							
				}
			});  
    });
});
//barcode end


//check_admin 
$('body').on('click', '.u-access-right', function(){
	var modify_class = 'filter-active';
	$(this).toggleClass(modify_class);
});

//admin - добавляем нового юзера 
$('body').on('click', '.add_user', function(){
	var parent = '.user_rights_parent';
	var $username_input = $('.add_user_name');
	var $password_input = $('.add_user_password');
	//имя пользователя
	var u_name = $username_input.val();
	//пароль пользователя
	var u_pass = $password_input.val();
	//роль пользователя
	var user_role = ls_init_radio_value('user_role') || null;
	//правила для просмотра страницы
	var access_page_list = collect_checked_acces(parent, 'ACCESS_PAGE');
	//правиоа для просмотра данных
	var access_data_list = collect_checked_acces(parent, 'ACCESS_DATA');

	console.log({access_page_list, access_data_list});
	
	//если поля не пустые иначе выводим ошибку
	if(u_name.length>0 && u_pass.length>=3  && user_role !== null) {
		$.ajax({
			url: '/core/action/admin/action/add_user.php',
			type: 'POST',
			dataType: 'json',
			data: {
				u_name: u_name,
				u_pass: u_pass,
				user_role: user_role,
				access_page_list: access_page_list,
				access_data_list: access_data_list
			},
			success: (data) => {
				if(data['success']) {
					show_success_modal('OK');
					//сбросить форму после регистрации
					reset_admin_form('user_role');
					$('.stock_list_tbody').prepend(data['append_row']);
				}
				if(data['error']) {
					show_error_modal(data['msg'] || data['error']);
				}
			}
		});
	} else {
		show_error_modal('Bütün sahələri doldurun');
	}
	//если пароль меньше или ранво 2 символам - выводим ошибку
	if(u_pass.length <= 2) {
		show_error_modal('Parol 2 simvoldan çox olmalıdır');
	}	
	//если не выбран роль пользователя
	if(user_role === null) {
		show_error_modal('İstifadəçi vəzifəsini seçin');
	}
});

//сбросить форму регистрации admin-panel
function reset_admin_form(radio_initial) {
	$('.add_user_name').val('');
	$('.add_user_password').val('');
	$('.u-access-right').each(function(){
		$(this).removeClass('filter-active');
	});	
	
	var user_role_default_value = $('.select_usser_role').attr('default-value');

	$('.select_usser_role').attr('id', '').removeClass('active-dropdown').val(user_role_default_value);

	reset_radio(radio_initial);
}

//собираем в массив все правила по ключу 
function collect_checked_acces(parent, rules) {
	/**
	*collect_checked_acces(parent, data-value);
	*в функциию передаеться 2 аргумента: 1 - имя родителя, по которому будет поиск
	*2 - значение атрибута data-access-type
	**/
	var collect = [];
	var $parent_check = $(`${parent}[data-access-type="${rules}"]`);

	$parent_check.find('.filter-active').each(function(){
		var res = $(this).data('accces-value');
		collect.push(res);
	});
	if(collect.length>0) {
		return collect;
	} else {
		collect.push('empty');
		return collect;
	}
}

//получить модально окно для редактирования данных пользователя
$('body').on('click', '.get_user_edit_modal', function(){
	var $this = $(this).closest('.user-list'); 
	var user_id =  $this.attr('id');

	$.ajax({
		url: '/core/action/admin/modal/order.php',
		type: 'POST',
		data: {
			modal_order: true,
			user_id: user_id
		},
		success: (data) => {
			addLeftPaddingModal();
			$this.addClass("o_product_selected");
			$('.modal_view_stock_order').html(data);

			var res = collect_checked_acces('.edit_user_rights_parent', 'ACCESS_DATA');			
			console.log({res});
		}
	});
});

//сохраняем изменение
$('body').on('click', '.update_user_info_btn', function(){
	var rules_list_parent = '.edit_user_rights_parent';

	//id пользователя
	var user_id = $('.modal_order_form').data('order-id');
	//имя пользователя
	var u_name = $('.edit_user_name').val();
	//пароль пользователя
	var u_pass = $('.edit_user_pass').val();
	//роль пользователя
	var user_role = ls_init_radio_value('upd_user_role');
	//доступ к страницам пользоватея
	var page_access = collect_checked_acces(rules_list_parent, 'ACCESS_PAGE');	
	//доступ к данным таблицы пользователя
	var data_access = collect_checked_acces(rules_list_parent, 'ACCESS_DATA'); 
	console.log({page_access, data_access});

	if(u_name.length>0 && u_pass.length >= 3 && user_role.length>0) {


		$.ajax({
			url: '/core/action/admin/action/update_user.php',
			type: 'POST',
			dataType: 'json',
			data: {
				user_upd: 'true',
				user_id: user_id,
				u_name: u_name,
				u_pass: u_pass,
				page_access: page_access,
				data_access: data_access,
				user_role: user_role
			},
			success: (data) => {
				if(data['success']) {
					show_success_modal('OK');
				}
				if(data['error']) {
					show_error_modal(data['error']);
				}
			}
		});	
	} else {
		show_error_modal('Bütün sahələri doldurun');
	}
	if(u_pass.length <= 2) {
		show_error_modal('Parol 2 simvoldan çox olmalıdır');
	}
});

//удаить пользователя
$('body').on('click', '.delete_user', function(){
	$this = $(this);
	//id - пользователя
	var user_id = $this.attr('id');
	$.ajax({
		url: '/core/action/admin/action/update_user.php',
		type: 'POST',
		dataType: 'json',
		data: {
			del_user: 'true',
			user_id: user_id
		},
		success: (data) => {
			alert(data['msg']);
			if(data['success']) {
				show_success_modal('ok');
			} else {
				show_error_modal('Ошибка');
			}
		}

	});

});