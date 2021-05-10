
$(document).ready(function(){

	$('body').on('click', '.close_modal_btn', function(){
		close_modal();
	});

});

function close_modal() {
	var $modal = $('.module_fix_right_side');
	$modal.removeClass('modal_show_anim')
		  .addClass('modal_hide_anim');


		  setTimeout(function(){
		  		$modal.find('.modal_view_stock_order').html('');
		  }, 200);
		  

		// $('.modal_view_stock_order').html('');
		// $('.main').attr("style", "padding-right: 0px");
		$('.add_profit_modal').fadeOut();
		//убираем активированный продукт
		remove_selected_product();
		$('.overlay').fadeOut();
		$('body').css('overflow', 'auto');
}

function addLeftPaddingModal() {
	var $modal = $('.module_fix_right_side');
	$modal.removeClass('modal_hide_anim')
		  .addClass('modal_show_anim');

	$('.overlay').fadeIn();
	$('body').css('overflow', 'hidden');				
}

$(document).ready(function(){
	$('body').on('click', '.delete_btn_link', function(){
		$('.delete_stock_module').show().attr("style", "display: flex;");
	});
});

$(document).ready(function(){
	$('body').on('click', '.module_delete_btn_cancle', function(){
		$('.delete_stock_module').hide();
	});
});


$(document).ready(function(){
	$('body').on('click', '.module_nav_btn', function (){
		$('.module_sidebar').toggleClass('open_menu');
		$('.module_nav_first_image').toggleClass('menu_close_img');
		$('.module_nav_second_image').toggleClass('menu_open_img');
		$('.modle_menu_btn').toggleClass('menu_btn_active');
	});
});



$(document).ready(function(){
	$('body').on('click', '.close_print_module', function(){
		$('.print_div').fadeOut();
	});
});


$(document).ready(function(){
	$('body').on('click', '.report_action', function(){
		var report_action_id = $(this).attr("id");
		$('.module_fix_right_side').fadeIn();
		$('.phone_modal_view').fadeIn();
		$('.akss_modal_view').fadeOut();
		$('.add_to_recipert').attr('id',report_action_id);
		$('.stock_return_accept_button').attr('id', report_action_id);
		$('.receipet_success').fadeOut();
		$('.stock_return_accept_form').fadeOut();
	});
});



$(document).ready(function(){
	$('body').on('click', '.akss_report_action', function(){
		var report_action_id = $(this).attr("id");
		$('.module_fix_right_side').fadeIn();
		$('.akss_modal_view').fadeIn();
		$('.phone_modal_view').fadeOut();
		$('.akss_stock_return_accpet_action_btn').attr('id', report_action_id);
		$('.akss_stock_return_accept_form').fadeOut();
	});
});



$(document).ready(function(){
	$('body').on('click', '.link_stock_return_btn', function(){
		$('.stock_return_accept_form').attr("style", "display: flex").fadeIn();
	});
});

$(document).ready(function(){
	$('body').on('click', '.akss_link_stock_return_btn', function(){
		$('.akss_stock_return_accept_form').attr("style", "display: flex").fadeIn();
	});
});


$(document).ready(function(){
	$('body').on('click', '.stock_return_cancle', function(){
		$('.stock_return_accept_form').fadeOut();
		$('.akss_stock_return_accept_form').fadeOut();
	});	
});


$(document).ready(function(){
	$('body').on('click', '.close_report_print', function(){
		$('.receipt_order_rerport_list').fadeOut();
		$(this).fadeOut();
	});
});


$(document).ready(function(){
	$('body').on('click', '.close_error_module_action', function(){
		$('.add_stock_module_error').fadeOut();
	});



	$('body').on('click', '.fastOptionOpenAction', function(){
		$('.select_option_name_wrp').fadeIn();
	});

	$('body').on('click', '.close_option_name', function(){
		$('.select_option_name_wrp').fadeOut();
	});
	
	$('body').on('click', '.selectOptionName', function(){
		let selectedoption = $(this).html();

		$('.add_stock_name_input').val(selectedoption);

		$('.select_option_name_wrp').fadeOut();
	});


	$('body').on('click', '.reminder_delete_hdr', function(){
		$('.reminder_wrapper_header').fadeOut();
	});



});





/**********update start******/

$(document).ready(function(){

	$(window).scroll(function(){
		var $height = $(this).scrollTop();
		var maxheight = 600;
		if($height > maxheight) {
			$('.scroll_to_top_wrapper').fadeIn().addClass('flex-cntr');
			$('body thead').css({'position': 'fiexd', 'left': '0', 'top' : '0'});
		} else {
			$('.scroll_to_top_wrapper').fadeOut().removeClass('flex-cntr');
		}
	});

	//при навелении на модально окно убирать прокуртку у боди
	$(document).on('click', '.scroll_top', function(){
		console.log('ds3232');
		var $body = $("html, body, .wrapper");
		 $body.stop().animate({scrollTop:0}, 500, 'swing', function(evt) {
			 // console.log('done');
		 });
	});


	//фильтруем данные инпута от муора
	$('body').on('keyup', '.order_input', function(){
		//получаем цену заказа
		var order_price = $('.order_price_action').val();
		
		//получаем количество заказа
		var $order_count = $('.order_count_action');

		//количество заказа	
		var order_count_value = $order_count.val();
		//функция очищает цену от лишнего 
		preg_order_price_value(order_price);

		//функция очищает количество от лишнего
		preg_order_count_value(order_count_value);

		//проверка на валидность
		product_count_not_valid(order_count_value, $order_count);

		var order_total_res = order_price*order_count_value;
		var order_total_res = order_total_res.toFixed(1);

		$('.get_order_action').removeClass('click');

		$('.show_total_sum_order_action').html(order_total_res);
		$('.total_sum_order_stock').val(order_total_res);

	});

	//если нажали на сблок с суммой заказа то фокусить цену 
	$('body').on('click', '.show_total_sum_order_action', function(){
		$('.order_price_action').focus();
	});


	//очищаем цену для заказа
	function preg_order_price_value(order_price) {
		//заменяем все запятне на точку 
		var order_price = order_price.replace(',', '.' );

		//удалаяем все буквы и символы кроме цифр
		var order_price = order_price.replace(/[^.\d]+/g,"")

		//удаляем все лишние точки и оставяем тоько одну
		var order_price = order_price.replace( /^([^\.]*\.)|\./g, '$1');

		$('.add_stock_submit').removeClass('click');
		$('.order_price_action').val(order_price);
		return order_price;	
	}

	//очишаю цену при доавлении в базу 
	$('body').on('keyup', '.input_preg_action', function(){
		add_price = $(this).val();
		//вызываем функция очищаем инпут
		preg_order_price_value(add_price);

		//получем очищеный инпут из функции
		var price = preg_order_price_value(add_price);
		
		//выводим в инпут
		$(this).val(price);

	});
	//очищаем количество заказа
	function preg_order_count_value(order_count) {
		//очищаем от точки\запятой и любого символа кроме цифр
		var order_count = order_count.replace(/[^.\d]+/g,"").replace(/[^,\d]+/g,"");

		//удаляем 0  в начале строки
		var order_count = order_count.replace(/^0/,'');

		$('.order_count_action').val(order_count);
	}

	//если количество товра не валидна
	function product_count_not_valid(order_count_value, $order_count) {
		if(order_count_value.length === 0 || order_count_value == 0) {
			//добавляем класс не активного инпута
			$order_count.addClass('not_valid_input');
		} else {
			//удаляем класс не активного инпута
			$order_count.removeClass('not_valid_input');			
		}
	}



	$('body').on('click', '.add_prfit_action', function(){
		$('.add_profit_modal').fadeIn().css('display', 'flex');
	});

});

function remove_selected_product() {
	$('.overlay').fadeOut();
	$('body').css('overflow', 'auto');
	$('.o_product_selected').each(function(){
		$('.o_product_selected').removeClass('o_product_selected');
	});
}


//прибавить/отнять количество товра дя акссеуаров
$(document).ready(function(){
	$('body').on('click', '.edit_custom_count', function(){
		$(this).hide('slow');
		$(this).parent().attr("style", "opacity: 1");
		$(this).parent().find('.edited_custom_stock_count').prop("disabled", false);
	});
});

//при изменении на страницк


// //если таблица пуста
// $('body').on('DOMSubtreeModified', '.stock_list_tbody', function(){
// 	table_data_empty_check();
// });


// $(document).ready(function(){

//         const MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
//         // target
//         const list = document.getElementById('main');
//         // options
//         const config = {
//             attributes: true,
//             childList: true,
//             characterData: true,
//             subtree: true,
//         };
//         // instance
//         const observer = new MutationObserver(function(mutations) {
//             // console.log(`mutations =`, mutations); // MutationRecord
//             mutations.forEach(function(mutation) {
//                 if (mutation.type === "childList") {
//                     if (mutation.target && [...mutation.addedNodes].length || mutation.target && [...mutation.removedNodes].length) {
// 						console.log('first');
// 						                    	console.log('second');
//                     	table_data_empty_check();
//                         // console.log(`A child node ${mutation.target} has been added!`, mutation.target);
//                     }
//                     // if (mutation.target && [...mutation.addedNodes].length) {
//                     // 	console.log('first');
//                     //     // console.log(`A child node ${mutation.target} has been added!`, mutation.target);
//                     // }
//                     // if (mutation.target && [...mutation.removedNodes].length) {
//                     // 	console.log('second');
//                     // 	table_data_empty_check();
//                     //     // console.log(`A child node ${mutation.target} has been removed!`, mutation.target);
//                     // }					
//                 }
//             });
//         });
//         observer.observe(list, config);
    
//  });


// //если изменен размен экрана
//  $(window).resize(function() {
// 	table_data_empty_check();
//  });

function table_data_empty_check() {
	//количество заголовков таблицы
	let integer = 0;
	//перебираем и получаем количество заголовков таблицы
	$('.stock_list_tbody').closest('table').find('thead').find('tr>th').each(function(){
		 integer++;
	});
	//от количества заголовков отнимаем 2 позиции, что бы выглядело красиво
	let th_count = integer - 2;	

	//добавляем атрибут в блок <результат табицы>
	$('.tfoot_header').attr('colspan', th_count);
	$('.tfoot_data').attr('colspan', 2);

	// обертка таблицы
	$table_parent = $('.stock_view_list');
	// таблица
	$table = $('.stock_table');
	// высота страницы
	var max_height = $(window).height() / 1.40;
	// высота таблицы
	var table_height = $table.height();
	// высота обёртки таблицы
	var prent_htgiht = $table_parent.height();

	// console.log({prent_htgiht, max_height});
	// console.log('высота table ' + $('.stock_table').height());

	// если высота таблицы больше чем высота документа, то добавляем полосу прокрутки
	// иначе убираем 
	// if( table_height > max_height) {
	// 	$table_parent.css({
	// 		'overflow' : 'auto'
	// 	});
	// } else {
	// 	$table_parent.css({
	// 		'overflow' : 'hidden'
	// 	});	
	// }

	$table_name = $('.stock_list_tbody');
	$empty_show_block = $('.empty_table_row');	

	var table = $table_name.html();

	if($table_name.length) {
		if(table.length <= 0) {
			$empty_show_block.show();
			// console.log('table have');
		} else {
			$empty_show_block.hide();
		}	
	}
	// console.log('ds');
}


function add_table_empty_block() {
	var table_row = '<div class="empty_table_row"></div>';
	var table_data = '<h3 class="table_empty_text">Məlumat tapılmadı</h3>';
	$('.stock_table').append(table_row);
	$('.empty_table_row').append(table_data);
	table_data_empty_check();
}








//************************test stats card report *******************************//


function get_stats_info(param, type) {
	$date_block = $('.stats-date');
	var param = $date_block.data('cur-date');
	var type = $('.stock_list_tbody').data('category');

	$.ajax({
		type: 'GET',
		url: 'core/pulgin/stats_card/stats_action/get_stats_data.php',
		data: {
			param: param,
			type: type
		},
		success: (data) => {
			$date_block.html(param);

			if(type == 'phone') {
				var cardData = generateCardObj_phone(data);
				console.log(cardData);
			}
			if(type === 'akss') {
				var cardData = generateCardObj_akss(data);
			}

			//собираем карточку
			generateCard(cardData);
			//добавляем на страницу
			renderToDom(cardData);

		}
	});

}
function generateCardObj_phone(data) {
	let cardData = [
		{
			title: 'Ümumi dövriyyə',
			value: data.total_money,
			img:   '<img src="/img/icon/manat-white.png" class="manat_icon_white_stats">',
			color: '--stats-card-primary-color: #8d47ff'
		},
		{
			title: 'Xeyir',
			value: data.total_profit,
			img:   '<img src="/img/icon/manat-white.png" class="manat_icon_white_stats">',
			color: '--stats-card-primary-color: #88d498'
		},
		{
			title: 'XERC',
			value: data.total_rasxod,
			img:   '<img src="/img/icon/manat-white.png" class="manat_icon_white_stats">',
			color: '--stats-card-primary-color: #de8f8f'
		},
		{
			title: 'Cəmi satış (sayı)',
			value: data.total_sell_count,
			img: '',
			color: '--stats-card-primary-color: #565264'
		}
	];
	return cardData;
}


function generateCardObj_akss(data) {
	let cardData = [
		{
			title: 'Ümumi dövriyyə',
			value: data.total_money,
			img:   '<img src="/img/icon/manat-white.png" class="manat_icon_white_stats">',
			color: '--stats-card-primary-color: #8d47ff'
		},
		{
			title: 'Xeyir',
			value: data.total_profit,
			img:   '<img src="/img/icon/manat-white.png" class="manat_icon_white_stats">',
			color: '--stats-card-primary-color: #88d498'
		},
		{
			title: 'Cəmi satış (sayı)',
			value: data.total_sell_count,
			img: '',
			color: '--stats-card-primary-color: #565264'
		}
	];

	return cardData;
}

function generateCard(data){

    var title = data.title;
    var value = data.value;
    var img = data.img;
    var color = data.color;


    return ('<li class="stas_card" style="'+color+'"><div class="stats--crad-header h3">'+title+'</div><div class="stats--card-value-box flex-cntr"><span class="stats--value">'+space_after_3rd_char(value)+'</span><span class="mark">'+img+'</span></div></li>');
  }

function clearCardList() {
	$('.stas_crad_list').empty();
}

function renderToDom(data){
	clearCardList();
  data.forEach(function(item){
    var card = generateCard(item);
    //render card to dom
    //console.log(card);
    $('.stas_crad_list').append(card);
  });
}


//делаем из обьекта строку и доабвляем проел после каждого 3 знака
function space_after_3rd_char(val) {
	var toStr = new String(val);

	var toStr = toStr.toString();

	var toStr = toStr.replace(/(\d)(?=(\d{3})+(\D|$))/g, '$1 ');

	console.log('space after 3rd char' + toStr);
	return toStr;
}
/************************************stats end********************************************************************/








/**********update end********/



//кастомынй селект
$('body').on('click', '.drop_down_btn', function(){
	var $this = $(this);
	//класс активной кнопки
	var click_button =  'click-dropdown';
	var slected_option = 'active-dropdown';
		$this.toggleClass(click_button);
		if($this.hasClass(click_button)) {
			$('.ls-select-option-list').each(function(){
				$(this).fadeOut();
			});
			$('.drop_down_btn').each(function(){
				$(this).not($this).removeClass(click_button);
			});
			$this.closest('.ls-select-list').find('.ls-select-option-list').fadeIn();		
		} else {
			$this.closest('.ls-select-list').find('.ls-select-option-list').fadeOut();
		}


	$('.drop_down_btn').each(function(){
		filter_angle_decore($(this));
	});

});

$('body').on('click', '.choice-option',function(){
	var $this = $(this);
	var selected_id = $this.attr('id');
	var selected_value = $this.attr('value');
	var $option_block  = $('.ls-select-option-list');
	var $drop_down_btn = $('.drop_down_btn');
	var click_button =  'click-dropdown';
	var active_dropdown = 'active-dropdown';
	var $reset_btn = $('.reset_option');
	$this.closest('.ls-select-list').find('.drop_down_btn')
		 .attr('id',  selected_id)
		 .attr('value', selected_value)
		 .removeClass(click_button)
		 .addClass(active_dropdown)
		 .closest('.select-drop-down')
		 .find('.reset_option').fadeIn();
	$this.closest('.ls-select-option-list').fadeOut();
	filter_angle_decore($this);
});

$('body').on('click', '.ls-reset-option', function(){
	$this = $(this);
	//var $reset_btn = $('.reset_option');
	var $drop_down_btn = $this.closest('.ls-select-list').find('.drop_down_btn');
	var active_dropdown = 'active-dropdown';
	var default_value_dropdown = $drop_down_btn.attr('default-value');
	$drop_down_btn.removeClass(active_dropdown)
				  .attr('value', default_value_dropdown)
				  .attr('id', '')
				  .closest('.ls-select-list');
	filter_angle_decore($this);					  
});




function filter_angle_decore($this) {
	var $filter_wrapper = $this.closest('.ls-select-list'); 
	var $filter_button = $filter_wrapper.find('.drop_down_btn');
	
	// console.log($filter_button);
	if($filter_button.hasClass('active-dropdown')) {
		$filter_wrapper.find('.reset-filter-icon')
		.removeClass([
			'rotate-unset',
			'rotate-180',
			'la-angle-down'
		]).addClass('la-times');

		console.log('11');
	} 
	
	if(!$filter_button.hasClass('active-dropdown')) {
		$filter_wrapper.find('.reset-filter-icon')
		.removeClass([
			'la-times',
			'rotate-180'
		]).addClass([
			'la-angle-down',
			'rotate-unset'
		]);

		console.log('22');
	}


	if($filter_button.hasClass('click-dropdown')) {
		$filter_wrapper.find('.reset-filter-icon')
		.removeClass([
			'la-times',
			'rotate-unset'
		]).addClass([
			'rotate-180',
			'la-angle-down'
		]);

		console.log('33');
	}

}


function reset_all_filter_add() {
	$('.drop_down_btn').each(function(){
		var def_value = $(this).attr('default-value');
		var active_dropdown = 'active-dropdown';

		$(this).removeClass(active_dropdown)
			   .attr('value', def_value)
			   .attr('id', '');

		filter_angle_decore($(this));	   

	});
}

$(document).ready(function(){
	//open filter hidden block
	$('body').on('click', '.open_filter_widjet', function(e){
		var $this = $(this); 
		var active_dropdown = 'active-dropdown';
		var filter_btn_active_mark = 'filter_active';
		var $filter_content = $('.filter_content');
		$this.toggleClass(filter_btn_active_mark);
		$filter_content.fadeToggle();
	});


	$(document).click(function (e){
		const $drop_down = $('.filter_buttons_wrapper');
		if(!$drop_down.is(e.target)
			 &&  $drop_down.has(e.target).length === 0) {
			show_hide_dropdown();
		}
	});

	//открывекм выпадающий списко еси инпут активный
	$('body').on('focusin', '.auto-cmplt-input', function(e){
		$(this).closest('.auto-cmplt-parent')
		.find('.auto-cmplt-select').fadeIn();

		console.log('focused');0
	}).focusout(function(){
		hide_autocomplte_list();
	});

	function show_hide_dropdown() {
		var $this = $('.open_filter_widjet');
		var filter_btn_active_mark = 'filter_active';
		var $filter_content = $('.filter_content');
		if($this.hasClass(filter_btn_active_mark)) {
			$this.removeClass(filter_btn_active_mark);
		} 
		$filter_content.fadeOut();
	}


	//закрываем все октрытые авто-списки
	function hide_autocomplte_list() {
		$('.auto-cmplt-select').each(function(){
			$(this).fadeOut();
		});					
	}


	//если пользователь выбрал вариант
	$('body').on('click', '.auto-cmplt-list', function(){
		var $this = $(this);

		//получем общий родитель
		var $parent = $('.auto-cmplt-parent');
		//получем инпут
		var $input = $('.auto-cmplt-input');
		//выбраный вариант
		var value = $this.text().trim();

		$this.closest($parent).find($input).val(value);
	});

	//кнопки радио для выбора одного варианта
	$('body').on('click', '.ls_radio', function(){
		$('.ls_radio').each(function(){
			$(this).removeClass('ls_radio_activ');
		});
		$(this).addClass('ls_radio_activ');
	});
});

//добавть прелодлаер элементу
function add_preloader($class) {
	var preloader_gif = '<div class="auto-cmplt-preloader"><img src="/img/icon/load.gif"></div>';

	$class.html(preloader_gif);
}

$('body').on('focus', '.add_stock_input', function(){
	$(this).closest().find('.add_stock_description').css('color', 'red');
});


//radio button start
//выбрать один из вариантов радиокнопок 
$('body').on('click', '.radio-button', function(){
	var $this = $(this);
	var radio_initial = $(this).attr('ls-radio-for');

	$(`.radio-button[ls-radio-for='${radio_initial}']`).each(function(){
		$(this).removeClass('radio-active');
	});
	$(this).addClass('radio-active');
});



//получаем выбраную radio кнопку
function ls_init_radio_value(initial) {
	var $radio_wrapper = $(`.radio-wrapper[ls-radio-initial='${initial}']`);
	var wrapper_initial = $radio_wrapper.attr('ls-radio-initial');

	var $get_active_btn = $radio_wrapper.find(`.radio-button.radio-active[ls-radio-for='${wrapper_initial}']`);


	var get_value = $get_active_btn.attr('ls-radio-value');
	return get_value;
}

//очищаем активыне radio
function reset_radio(radio_initial) {
	$(`.radio-wrapper[ls-radio-initial='${radio_initial}']`).find('.radio-button').each(function(){
		$(this).removeClass('radio-active');
	});	
}
//radio button end


//проверка данных на json
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}


$('body').on('click', '.open_gorup_list', function(){
	$(this).closest('.gorup-list-product-info').find('.group-list').fadeToggle();
});


//добавляем результат и данные таблицы
function update_tfoot_reuslt(data) {
	$('.tfoot').find('.tfoot_body').html(data);
}

//показыать количество ВСЕХ активных фильтров
function display_active_filter_count() {
	let active_filter_count = 0;

	$('.ls-select-list-option').find('.filter-active').each(function(){
		active_filter_count++;
	});

	if(active_filter_count > 0) {
		$('.filter-count').css('display', 'flex').text(active_filter_count);
	} else {
		$('.filter-count').fadeOut();
	}
	// alert(active_filter_count);
}

//показывать количество выбранных фильров выбранной категории
function display_active_this_filter_count($this) {
	let count = 0;

	$this.closest('.ls-select-list-option').find('.filter-active').each(function(){
		count++;
	});

	if(count > 0) {
		$this.closest('.filter_return_list')
			 .find('.filter_check_count')
			 .css('display', 'flex')
			 .html(count);
	} else {
		$this.closest('.filter_return_list')
			.find('.filter_check_count')
			.fadeOut();
	}
}

//показать индинтификатор страницы
function get_page_param(attr) {
	var data_page = $('.stock_list_tbody').attr(attr);
	return data_page;
}

//сбрасываем страницу и очищаем все изменение на фронте
function clear_page() {
	//очищаем поиск
	$('.search_input').val('');

	$('.filter-count').fadeOut();

	$reset_this = $('.search_reset');

	var reset_data_sort = $reset_this.attr('data-sort');
	var reset_value = $reset_this.attr('value');
	var reset_sort_value = $reset_this.attr('data-sort-value');

	search_item_stock(reset_sort_value, reset_data_sort);
}

//сбрасываем поиск 
$('body').on('click', '.search_reset', function() {
	clear_page();
});
