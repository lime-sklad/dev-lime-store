$.ajaxSetup({
    beforeSend: function( ){
		pageData.preloaderShow();
    },
    complete: function() {
		pageData.preloaderHide();
    } 
});

/** START report */

//удаление отчёта
$(document).on('click', '.report-return-btn', function(){
    //id - отячёта
    var report_order_id = $('.report_order_id').data('id');
  
    $.ajax({
        url: 'core/action/report/delete_report.php',
        type: 'POST',
        data: {
            report_id: report_order_id
        },
        dataType: 'json',
        success: (data)=> {
            if(data.success) {
				pageData.alert_notice('success', data.success);
				pageData.rightSideModalHide();
				pageData.overlayHide();

                $(`.get_report_order_id[data-sort-value="${report_order_id}"]`).closest('.stock-list').remove();
            }
            if(data.error) {
               pageData.alert_notice('error', data.error);
            }
        }
    });
});


/** END report */


/** 
 * MENU START
 * */ 

$('body').on('click', '.get_page_action', function(){
    //смотри в option.php
    //получаем ссылку на страницу
    var $result = $('.main');
    var tab = $(this).data('tab');
    var data_page = $(this).data('page-route');

	

    $.ajax({
        type: 'POST',
        url:  'page/route.php',
        data: {
            tab: tab,
            data_page_route: data_page
        },
        success: (data) => {
            //проверка данных на json
            if(data.error) {
                notice_modal('text', 'error');
            } else {
                $result.html(data);
            }

            ui_selected_sidebar(tab);
            ui_selected_tab();
            visible_menu('hide');

			const content_title = $('.content__title').text();

			$('title').html(`Lime Store > ${content_title}`);
        }			
    });
});


//tab
$('body').on('click', '.tab-button', function(){
	console.log('ds');
    $tab_active = 'tab_activ';
    var $result = $('.main')
    var tab = $(this).data('tab');
    var data_page = $(this).data('page');

		$.ajax({
			url:  'page/route.php',
			type: 'POST',
			data: {				
				page: data_page,
				tab: tab,
			},
			success: (data) => {
                $('.tab-button').removeClass($tab_active);
				$('.content').remove();
				$(this).addClass($tab_active);
                ui_selected_tab();
                //проверка данных на json
                if(data.error) {
                    notice_modal('text', 'error');
                } else {
                    $result.append(data);
                }
			}
		});
	 			
});

//endtab


/** MENU END */

/** send filter / search / autocomplete start */

//фильтрация товара
function send_filter(filter_list) {
	//переключаем состяние на активный
	$.ajax({
		type: 'POST',
		url:  'core/action/stock/get_filter_stock.php',
		data: {
			id: filter_list,
			page: pageData.page(),
			type: pageData.type()
		},
        dataType: 'JSON',
		success: (data) => { 

			//выводим в талицу данные
			if(data.table) {
				pageData.innerTable(data.table);	
			}
			if(data.total) {
				pageData.innerTableFooter(data.total);
            }
		}				
	});
}

function send_autocomplete($this) {
    var $delay = 450;
	var min_value_length = 1;   

	var $search_container = $this.closest('.search-container');
	var $append_to = $this.closest($search_container).find('.search-list-content');
    var $preloader = $this.closest($search_container).find('.preloader');
	var search_data = $this.val().trim();
	var data_name = $this.attr('data-name');
	var autocmplt_type = $append_to.attr('data-auto-cmplt-type');
	var $table = $('.table-list');

	console.log(search_data);
	console.log(pageData.type());
	if(search_data.length > min_value_length) {
		$preloader.addClass('flex-cntr').removeClass('hide');
		clearTimeout($this.data('timer'));
		$this.data('timer', setTimeout(function(){
			$.ajax({
				type: 'POST',
				url: 'core/action/autocomplete.php',
				data: {
					value: search_data,
					action: data_name,
					page: pageData.page(),
					type: pageData.type(),
					autocmplt_type: autocmplt_type
				},
				beforeSend: () => {
				},
				complete: () => {
					$preloader.removeClass('flex-cntr').addClass('hide');
				},
				success: (data) => {
					if(data.length <= 0) {
						$append_to.html('Heç nə tapılmadı');
					} else {
						$append_to.html(data);
					}
				}
			});

			console.log($this.data('timer'));
		}, $delay));					
	} else {
		$append_to.html('no result');
	}	
}

$(document).on('click', '.search-item', function(){
    reset_all_filter();
	//делаем поиск по значению 
	// var search_item_value = $(this).find('.stock-info-text').text();
	var search_item_value = $(this).data('sort-value');

	//for report sort data 
    var sort_data = $(this).data('sort');
    
    var $search_main_table = $('.stock_list_tbody');
	//тут мы получаем тип таблицы (terminal, stock, report и тд)

	$.ajax({
		type: 'POST',
		url: 'core/action/search.php',
		data: {
			search_item_value	: search_item_value, 
			page				: pageData.page(), 
			type			    : pageData.type(),
			sort_data 			: sort_data
		},
		dataType: 'json',
		success: (data) => {
			//выводим в талицу данные
			if(data.table) {
				pageData.innerTable(data.table);	
			}
			if(data.total) {
				pageData.innerTableFooter(data.total);
			}
			console.log('hello world');

		}			
	}); 
});

/** end send filter */



/** order start*/
$('body').on('click', '.info-stock', function(){
	$modal = $('.modal_view_stock_order');

	pageData.preloaderShow();
	pageData.overlayShow();
	$('.get_order_action').removeClass('click');

	//получаем id проддукта от родительсокого эелемента
	var product_id = $(this).closest('.stock-list').attr("id");		
	//report_order_id
	var order_id = $(this).find('.get_report_order_id').attr('data-sort-value');

	$.ajax({
		type: 'POST',
		url: 'core/action/modal/order.php',
		data:{
			product_id : product_id,
			order_id: order_id, 
			type  : pageData.type(), 
			page  : pageData.page()
		},
		success: (data) => {
			pageData.rightSideModal(data);
		}			

	});
});
/** end order */

/** update stock */
$('body').on('click', '.submit-save-stock', function() {
	let prepare_data = {};

	const stock_id = $('.edit-stock-id').data('id');

	prepare_data['upd_product_id'] = stock_id;

	$('.edit').each(function(){
		if($(this).data('fields-name') && $(this).hasClass('edited')) {
			var data_name = $(this).data('fields-name');
			var val = $(this).val();
			prepare_data[data_name] = val;
		}
	});

	$.ajax({
		type: 'POST',
		url: 'core/action/stock/update_product.php',
		data: prepare_data,
		dataType: "json",
		success: (data) => {
			// console.log(data);
			var error 	= data['error'];
			var success = data['success'];

			console.log(prepare_data);

			if(error) {
				pageData.alert_notice('error', error)
			}

			if(success) {
				pageData.alert_notice('success', 'Ок');
				
				for (key in prepare_data) {
					pageData.update_table_row(key, prepare_data[key], stock_id);
				}

				// update_table_row
			}
		}			

	});

});
/** end update stock */


/** удалить товар start */
$(document).on('click', '.delete-stock', function() {
	const id = $(this).data('delete-id');

	$.ajax({
		type: 'POST',
		url: 'core/action/stock/delete_products.php',
		data: {stock_id: id},
		dataType: 'json',
		success: (data) => {
			if(data.success) {
				pageData.alert_notice('success', data.success);
				pageData.rightSideModalHide();
				pageData.overlayHide();

				var $stock = $(`.stock-list#${id}`); 

				$stock.hide(1000, function() {
					$stock.remove();
				});
			}
			if(data.error) {
				pageData.alert_notice('error', data.error);
			}

		}
	});
});
/** удалить товар end */



/** добавить товар товар start */
$('body').on('click', '.submit-stock-addd-form', function() {
	let prepare_data = {};

	if(is_required_input($('.form-input'))) {
		$('.add-stock').each(function(){
			if($(this).data('fields-name')) {
				var data_name = $(this).data('fields-name');
				var val = $(this).val();
				prepare_data[data_name] = val;
			}
		});

		$.ajax({
			type: 'POST',
			url: 'core/action/stock/add_stock.php',
			data: prepare_data,
			dataType: "json",
			success: (data) => {
				// console.log(data);
				var error 	= data['error'];
				var success = data['success'];

				console.log(prepare_data);

				if(error) {
					pageData.alert_notice('error', error);
				}

				if(success) {
					pageData.alert_notice('success', 'Ок');
					$('.form-input').val('');
				}
			}			

		});
	}
});

/** добавить товар end */


// добавить категорию
$('body').on('click', '.add-submit-category', function() {
	let prepare_data = {};

	if(is_required_input($('.form-input'))) {
		$('.add-stock').each(function(){
			if($(this).data('fields-name')) {
				var data_name = $(this).data('fields-name');
				var val = $(this).val();
				prepare_data[data_name] = val;
			}
		});

		$.ajax({
			type: 'POST',
			url: 'core/action/category/add_category.php',
			data: {
				post_data: prepare_data,
				page: pageData.page(),
				type: pageData.type()
			},
			dataType: "json",
			success: (data) => {
				// console.log(data);
				var error 	= data['error'];
				var success = data['success'];
				
				if(error) {
					pageData.alert_notice('error', error);
				}

				if(success) {
					pageData.alert_notice('success', 'Ок');
					$('.form-input').val('');

					if(data.table) {
						return pageData.prependTable(data.table);
					}
				}
			}			

		});
	}
});


// изменить категорию
$('body').on('click', '.submit-save-category', function() {
	let prepare_data = {};

	const category_id = $('.category-id').data('id');

	prepare_data['category_id'] = category_id;

	$('.edit').each(function(){
		if($(this).data('fields-name') && $(this).hasClass('edited')) {
			var data_name = $(this).data('fields-name');
			var val = $(this).val();
			prepare_data[data_name] = val;
		}
	});

	$.ajax({
		type: 'POST',
		url: 'core/action/category/update_category.php',
		data: prepare_data,
		dataType: "json",
		success: (data) => {
			// console.log(data);
			var error 	= data['error'];
			var success = data['success'];

			console.log(prepare_data);

			if(error) {
				pageData.alert_notice('error', error)
			}

			if(success) {
				pageData.alert_notice('success', 'Ок');
				
				for (key in prepare_data) {
					pageData.update_table_row(key, prepare_data[key], category_id);
				}
			}
		}			

	});

});

/** удалить категория start */
$(document).on('click', '.delete-category', function() {
	const id = $(this).data('delete-id');

	$.ajax({
		type: 'POST',
		url: 'core/action/category/delete_category.php',
		data: {id: id},
		dataType: 'json',
		success: (data) => {
			if(data.success) {
				pageData.alert_notice('success', data.success);
				pageData.rightSideModalHide();
				pageData.overlayHide();

				var $stock = $(`.stock-list#${id}`); 

				$stock.hide(1000, function() {
					$stock.remove();
				});
			}
			if(data.error) {
				pageData.alert_notice('error', data.error);
			}

		}
	});
});
/** удалить категория end */





// добавить поставщика
$('body').on('click', '.add-submit-provider', function() {
	let prepare_data = {};

	if(is_required_input($('.form-input'))) {
		$('.add-stock').each(function(){
			if($(this).data('fields-name')) {
				var data_name = $(this).data('fields-name');
				var val = $(this).val();
				prepare_data[data_name] = val;
			}
		});

		$.ajax({
			type: 'POST',
			url: 'core/action/provider/add_provider.php',
			data: {
				post_data: prepare_data,
				page: pageData.page(),
				type: pageData.type()
			},
			dataType: "json",
			success: (data) => {
				// console.log(data);
				var error 	= data['error'];
				var success = data['success'];
				
				if(error) {
					pageData.alert_notice('error', error);
				}

				if(success) {
					pageData.alert_notice('success', 'Ок');
					$('.form-input').val('');

					if(data.table) {
						return pageData.prependTable(data.table);
					}
				}
			}			

		});
	}
});

// изменить категорию
$('body').on('click', '.submit-save-provider', function() {
	let prepare_data = {};

	const provider_id = $('.provider-id').data('id');

	prepare_data['provider_id'] = provider_id;

	$('.edit').each(function(){
		if($(this).data('fields-name') && $(this).hasClass('edited')) {
			var data_name = $(this).data('fields-name');
			var val = $(this).val();
			prepare_data[data_name] = val;
		}
	});

	$.ajax({
		type: 'POST',
		url: 'core/action/provider/update_provider.php',
		data: prepare_data,
		dataType: "json",
		success: (data) => {
			// console.log(data);
			var error 	= data['error'];
			var success = data['success'];

			console.log(prepare_data);

			if(error) {
				pageData.alert_notice('error', error)
			}

			if(success) {
				pageData.alert_notice('success', 'Ок');
				
				for (key in prepare_data) {
					pageData.update_table_row(key, prepare_data[key], provider_id);
				}
			}
		}			

	});
});


/** удалить поставщик start */
$(document).on('click', '.delete-provider', function() {
	const id = $(this).data('delete-id');

	$.ajax({
		type: 'POST',
		url: 'core/action/provider/delete_provider.php',
		data: {id: id},
		dataType: 'json',
		success: (data) => {
			if(data.success) {
				pageData.alert_notice('success', data.success);
				pageData.rightSideModalHide();
				pageData.overlayHide();

				var $stock = $(`.stock-list#${id}`); 

				$stock.hide(1000, function() {
					$stock.remove();
				});
			}
			if(data.error) {
				pageData.alert_notice('error', data.error);
			}

		}
	});
});
/** удалить поставщик end */