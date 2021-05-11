$.ajaxSetup({
    beforeSend: function( ){
		// console.log('before send ajax');
        preloader_state('show');
    },
    complete: function() {
		// console.log('complete ajax');
        preloader_state('hide');
    } 
});

/** START report */

//удаление отчёта
$(document).on('click', '.delete_report', function(){
    //id - отячёта
    var report_order_id = $('.report_order_id').data('id');
  
    $.ajax({
        url: '/core/action/report/delete_report.php',
        type: 'POST',
        data: {
            report_id: report_order_id
        },
        dataType: 'json',
        success: (data)=> {
            if(data.success) {
                close_modal();
                show_success_modal(data.success);
                $(`.get_report_order_id[data-sort-value="${report_order_id}"]`).closest('.stock-list').remove();
            }
            if(data.error) {
                show_error_modal(data.error);
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
        url:  '/page/route.php',
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
        }			
    });
});


//tab
$('body').on('click', '.tab-button', function(){

    $tab_active = 'tab_activ';
    var $result = $('.content');
    var tab = $(this).data('tab');
    var data_page = $(this).data('page');

		$.ajax({
			url:  '/page/route.php',
			type: 'POST',
			data: {				
				page: data_page,
				tab: tab,
			},
			success: (data) => {
                $('.tab-button').removeClass($tab_active);
                $(this).addClass($tab_active);
                ui_selected_tab();
                //проверка данных на json
                if(data.error) {
                    notice_modal('text', 'error');
                } else {
                    $result.html(data);
                }
			}
		});
	 			
});

//endtab


/** MENU END */

/** send filter / search / autocomplete start */

//фильтрация товара
function send_filter(filter_list) {

   
    var $table_body = $('.stock_list_tbody');
    
	//переключаем состяние на активный
	var data_page = get_page_param('data-stock-page');
	var data_type = get_page_param('data-stock-type');    
	$.ajax({
		type: 'POST',
		url:  '/core/action/get_filter_stock.php',
		data: {
			id: filter_list,
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
				// update_tfoot_reuslt(data.total);
            }
		}				
	});
}

function send_autocomplete($this) {
    var $delay = 450;
	var $search_container = $this.closest('.search-container');
	var $append_to = $this.closest($search_container).find('.search-list-content');
    var $preloader = $this.closest($search_container).find('.preloader');
	var search_data = $this.val().trim();
	var data_name = $this.attr('data-name');
	var autocmplt_type = $append_to.attr('data-auto-cmplt-type');
	var $table = $('.table-list');
	var page = $table.attr('data-stock-page');
	var type = $table.attr('data-stock-type'); 

	console.log(search_data);
	if(search_data.length > 1) {
		$preloader.addClass('flex-cntr').removeClass('hide');
		clearTimeout($this.data('timer'));
		$this.data('timer', setTimeout(function(){
			$.ajax({
				type: 'POST',
				url: '/core/action/autocomplete.php',
				data: {
					value: search_data,
					action: data_name,
					page: page,
					type: type,
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
    
    let $search_main_table = $('.stock_list_tbody');
	//тут мы получаем тип таблицы (terminal, stock, report и тд)
	let search_from 		= $search_main_table.attr("data-stock-page");	
	let search_product_cat  = $search_main_table.attr("data-stock-type");

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
				// update_tfoot_reuslt(data.total);
			}
			console.log('hello world');

		}			
	}); 
});

/** end send filter */