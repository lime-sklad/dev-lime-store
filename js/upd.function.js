/** GLOBAL START */
//показать индинтификатор страницы
$(document).ready(function(){

  // delete this
  // var image_num = Math.floor(Math.random() * (8 - 1) + 1);

  const image_name = [
    '2.jpg',
    '4.jpg',
    '5.jpg',
    '10.jpeg',
    '11.jpg'
  ];

  const random_img = Math.floor(Math.random() * image_name.length);

  $('.menu').css({
    'background-image' : `url(img/pattern/${image_name[random_img]})`
  });

  //delete this


  pageData = {
    page: function() {
      // получаем страницу
      return $('.table').find('.table-list').data('stock-page');    
    },
    type: function() {
      // получаем тип данных
      return $('.table').find('.table-list').data('stock-type');
    },
    innerTable: function(data) {
      // заполняем таблицу данными 
      $('.table-list').html(data);
    },
    prependTable: function(data) {
      // заполняем таблицу данными 
      $('.table-list').prepend(data);
    },    
    appendTable: function(data) {
      // заполняем таблицу данными 
      $('.table-list').append(data);
    },    
    update_table_row: function(key, value, id) {
      const $this = $(`#${id}.stock-list`);
      const amimate_delay = 1500;
      switch (key) {
        case 'product_name':
          $this.find('.res-stock-name').find('.stock-list-title').html(value);
          break;

        case 'product_description':
          $this.find('.res-stock-description').find('.stock-list-title').html(value);
          break;  
          
        case 'product_first_price':
          $this.find('.res-stock-first-price').find('.stock-list-title').html(value);
          break;    

        case 'product_second_price':
          $this.find('.res-stock-second-price').find('.stock-list-title').html(value);
          break;  

        case 'change_product_count':
          $this.find('.res-stock-count').find('.stock-list-title').html(value);
          break; 
          
        case 'plus_minus_product_count':
          $this.find('.res-stock-count').find('.stock-list-title').html(value);
          break;            

        case 'provider_name_text':
          $this.find('.res-stock-provider').find('.stock-list-title').html(value);
          break;  

        case 'category_name_text':
          $this.find('.res-stock-category').find('.stock-list-title').html(value);
          break;   
          
        case 'upd_category_name': 
          $this.find('.res-category-name').find('.stock-list-title').html(value);
          break;  

        case 'upd_provider_name':
          $this.find('.res-edit-provider-name').find('.stock-list-title').html(value);

        case 'upd_rasxod_description':
          $this.find('.res-rasxod-description').find('.stock-list-title').html(value);
          break;
        
        case 'upd_rasxod_amount': 
          $this.find('.res-rasxod-amount').find('.stock-list-title').html(value);
          break;      
      }



    $this.addClass('modify', amimate_delay);

    setTimeout(() => {
      $this.removeClass('modify');
    }, amimate_delay);


    },
    innerTableFooter: function(data) {
      //заполняем футор таблицы данными
      $('.tfoot_body').html(data);
    },
    preloaderShow: function() {
      $('.body_prelodaer').find('.preloader').removeClass('hide').addClass('flex-cntr'); 
    },
    preloaderHide: function() {
      setTimeout(function(){
        $('.body_prelodaer').find('.preloader').removeClass('flex-cntr').addClass('hide');
      }, 260);
    },
    rightSideModal: function(data) {
      var $modal_wrp = $('.module_fix_right_side');
      
      $modal_content = $modal_wrp.find('.modal_view_stock_order');
      $modal_wrp.removeClass(['animate__slideOutRight', 'hide'])
                .addClass('animate__slideInRight');
      $modal_content.html(data);
    },
    rightSideModalHide: function() {
      $modal_wrp = $('.module_fix_right_side');
      
      $modal_wrp.removeClass('animate__slideInRight')
                .addClass('animate__slideOutRight')
                .find('.modal_view_stock_order');

      // с задержкой в 300мс удаляем содержимое
      setTimeout(() => {
        $modal_wrp.find('.modal_view_stock_order').empty();
      }, 300);
                

    },
    overlayShow: function() {
      $('.overlay').show();
    },  
    overlayHide: function() {
      $('.overlay').hide();
    },
    overlayToggle: function() {
      $('.overlay').toggle();
    },
    alert_notice: function(type, text) {
      var $notice = $('.notice');  
      
      $notice.addClass('notice-active').html(text);

      setTimeout(() => {
        $notice.removeClass('notice-active');
      }, 2500);


      switch (type) {
          case 'success':
              $notice.removeClass('error-notice').addClass('success-notice');
            break;
          case 'error': 
              $notice.removeClass('success-notice').addClass('error-notice');
          default:
            break;
        }
    }
  };

});
/** GLOBAL END  */

/** Валидация инпутов start */
$('body').on('focusout input', '.input-validate-length', function(){
  var val = $(this).val();
  input_validate_lenght(val, $(this));
});

$('body').on('focusout input', '.input-required', function(){
  var val = $(this).val();
  val.trim().length == 0 ? $(this).addClass('input-required-error') : $(this).removeClass('input-required-error');
});

$('body').on('focusout input', '.input-validate-price', function(){
  var val = $(this).val();
  var preg_val = input_validate_price(val);
  $(this).val(preg_val);
});

$('body').on('focusout keyup input', '.input-validate-count', function(){
  var $this = $(this);
  var val = $this.val();
  var preg_val = input_validate_count(val);

  $this.val(preg_val);
});

function input_validate_lenght(val, $this) {
  val.trim().length == 0 ? alert_vlidate_notice($this) : hide_validate_notice($this);
}

function input_validate_price(price) {
  var price = price.replace(',', '.' );
  var price = price.replace(/[^.\d]+/g,"");
  var price = price.replace( /^([^\.]*\.)|\./g, '$1');
  return price; 
}

function input_validate_count(count) {
  var count = count.replace(/[^.\d]+/g,"").replace(/[^,\d]+/g,"");
  var count = count.replace(/^0/,'');
  return count;
}

function input_validate_min_max_count(min, max, $this) {
  var val = $this.val();
  var preg_val = input_validate_count(val);

  if(preg_val && preg_val <= min) {
    $this.val(min);
  }
  else if(preg_val > max) {
    $this.val(max);
  } else {
    $this.val(preg_val);
  }
}



//валидация инпутов
function validate_all_input($item) {
  if($item.hasClass(['input-validate-length' || 'input-validate-price' || 'input-validate-count'])) {
    $item.trigger('input', 'keyup');
    
    if($('.input-validate-error').length) {
      pageData.alert_notice('error', 'заполните все поля');
      return false;
    }
    return true;  
  }
}

//валидация обьязательных полей
function is_required_input($item) {
  if($item.hasClass('input-required')) {
    $item.trigger('input', 'keyup');
  }

  if($item.hasClass('input-required-error')) {
      pageData.alert_notice('error', 'заполните все поля');
      return false;
  } else {
    return true;
  }
}



function alert_vlidate_notice(el) {
  hide_validate_notice(el);
  el.addClass('input-validate-error');
}

function hide_validate_notice(el) {
  el.removeClass('input-validate-error');
  el.parent().find('.warning-notice').remove();
}
/** Валидация инпутов end  */



/** menu start 
 * 
 * показывать бокове меню при наведении с задержкой в 700 мс 
 * 
 */
$(function () {
    let timeoutId = null;
    $(".sidebar").hover(
      function () {
        timeoutId = setTimeout(() => {
          $(this).addClass("sidebar_hovered");
        }, 500);
      },
      function () {
        // change to any color that was previously used.
        clearTimeout(timeoutId);
        $(this).removeClass("sidebar_hovered");
      }
    );
});

//при нажатии убираем активуню вкладку в боковом меню и открываем основное меню
$('body').on('click', '.get_main_page', function(){
  $('.sidebar-list').removeClass('sidebar-active');  
  visible_menu('show');
});

/** открываем меню */ 
function visible_menu(param) {
  var class_list = [
    'menu--active',
    'animate__animated',
    'animate__faster', 
    'animate__slideInRight '
  ];

  if(param == 'show') {
    $('.menu').addClass(class_list);
  }
  if(param == 'hide') {
    $('.menu').removeClass(class_list);
  }
}

//левое бокове меню
function ui_selected_sidebar(tab) {
  $('.sidebar-item').removeClass('sidebar-active');
  $(`.sidebar-item[data-tab="${tab}"]`).addClass('sidebar-active');
}

//активируем вкладку
function ui_selected_tab() {
  $active_tab = $('.tab_activ');

  $active_tab.ready(function(){
    var $tab = $active_tab.closest('.tab');
      
    // //настроить ширину для меню навигации вкладок
      var active_tab_width  = $tab.width();
      var offset = $tab.position();      
    
      $('.tab-selected-mark').css({
        width : active_tab_width,
        left : offset.left
      });
  });
}

/** menu start */
function notice_modal() {
}

//при навелении на модально окно убирать прокуртку у боди
$(document).on('click', '.scroll_top', function(){
	var $body = $("html, body, .wrapper, .menu");
	 $body.stop().animate({scrollTop:0}, 500, 'swing', function(evt) {
		 // console.log('done');
	 });
});



/** start widget button */

//открывам виджет и закрываем остальные
$(document).on('click', '.area-button', function(){
  var $this = $(this);
  var $button = $('.area-button'); 

  var content_modify_class = [
    'animate__animated',
    'animate__lsFadeIn25',
    'animate__faster',
    'area-active'
  ];

  var area_active = 'area-active';

  $this.toggleClass(area_active);

  open_dropdown($this, content_modify_class);
});


$(document).on('focusin', '.area-input', function(){
  $this = $(this);
  var area_active = 'area-active';

  var content_modify_class = [
    'animate__animated',
    'animate__lsFadeIn25',
    'animate__faster'
  ];

  $this.addClass(area_active);
  open_dropdown($this, content_modify_class);
});

$(document).on('keydown keyup', '.search-auto, .scroll-auto', function(e){
  let keyCode = {
    'up': e.key == 'ArrowUp',
    'down': e.key == 'ArrowDown',
    'enter': e.key == 'Enter',
    'tab': e.key == 'Tab'
  };

  if(keyCode.up || keyCode.down || keyCode.enter || keyCode.tab) {
    e.preventDefault();

    if(e.type == 'keydown') {
      let node;
      let unselect;
  
      var list = $(this).closest('.search-container').find('.search-content').find('.search-list-content li');
      
      var modify_selected = 'selected';
      var node_selected = $(list).find('.selected');
      var children_element = $('.select-item');
  
      let get_selected_node = list.find(node_selected);

      if(keyCode.enter) {
        get_selected_node.trigger('click');
        $(this).blur();
      }

      unselect = get_selected_node.removeClass(modify_selected);
      ui_unselect_nav(unselect);
  
      if(keyCode.down || keyCode.tab) {
        if (node_selected.parent().next().length == 0) {
          node = list.first();
        } else {
          node = node_selected.parent().next();
        }
      }
  
      if(keyCode.up) {
        if(node_selected.parent().prev().length == 0) {
          node = list.last();
        } else {
          node = node_selected.parent().prev();
        }
      }
      
      if(node) {    
        $('.wrapper').css('overflow', 'hidden');
        var this_node = node.children(children_element);

        if(this_node.length) {
          this_node[0].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
        }
        
        ui_select_nav(this_node);
      }   
    }    
  } else {
    if(e.type == 'keyup' && $(this).hasClass('search-auto')) {
      send_autocomplete($(this));
    }
  }
});

//нужно что бы закрыть выпадающий список
$(document).on('click', '.selectable-search-item.selected', function(){
  let $input = '';
  var data_text = $(this).children('.widget__button-text').text().trim();
  var $parent = $(this).closest('.search-container');
  


  if($parent.find('.search-auto').length) {
    $input = $parent.find('.search-auto');
    send_autocomplete($input);
    reset_area();
  }

  if($parent.find('.scroll-auto').length) {
    console.log('22');
    $input = $parent.find('.scroll-auto');
  }

  if($parent.find('.input-select').length) {
     $input = $parent.find('.input-select');
  }

  $input.val(data_text);
});

//при клике закрываем выпадающий список
$(document).on('click', '.area-closeable', function(){
  reset_area();
});
          
$(document).on('click', '.reset-search', function(){
  $this = $(this);
  var $parent = $this.closest('.search-container');
  var $input = $parent.find('.search-action');
  //костыль, исправить потом (что бы сбросить поиск, отправляем пустой массив фильтров которе возвращает дефолтноные данные)
  send_filter([]);
});

$(document).on('click', '.reset-input', function(){
  var $input = $(this).closest('.input-container, .search-container, .area-container, .search-container').find('.input');

  reset_input($input);
});


function reset_input($input) {
  $input.val('');
} 

$(document).on('mouseenter', '.select-items', function(){
  $parent = $(this).closest('.select-list');
  var $removebl = $parent.find('.selected');
  var $this = $(this).children('.selectable-search-item');
  ui_unselect_nav($removebl);
  ui_select_nav($this);
});

function ui_select_nav($this) {
  $this.addClass('selected'); 
  $('.wrapper').css('overflow', 'auto');
}

function ui_unselect_nav($this) {
  $this.removeClass('selected');
}

function open_dropdown($this, content_modify_class) {
  reset_area($this);
  var area_container = '.area-container';
  var area_content = '.area-content';
  var area_active = 'area-active';
  $content = $this.closest(area_container)
                      .find(area_content)
                      .first()
                      .addClass(content_modify_class);

  if($this.hasClass(area_active)) {
    $content.addClass(content_modify_class).show();
  } else {
    $content.removeClass(content_modify_class).hide();
  }
  // console.log('hello');
}

//закрываем все открыте виджеты 
function reset_area($t) {
  var sub_area = 'sub-area';
  var $sub_area_btn = $('.sub-area');
  var $area_button = $('.area-button, .area-input');
  var area_active = 'area-active';
  var area_container = '.area-container';
  var area_content = '.area-content';

  if($t && $t.hasClass(sub_area)) {
    $button = $sub_area_btn;
  } else {
    $button = $area_button;
  }

  $button.each(function(){
    if($(this).not($t).hasClass(area_active)) {
      $(this).removeClass(area_active).closest(area_container).find(area_content).first().hide();
    }
  });
}

//при нажатии на любое место закрываем виджеты
$(document).mouseup(function(e) {   
  var button = $('.area-button, .area-input');
  var container = $('.area-content');
    // if the target of the click isn't the container nor a descendant of the container
  if ( !button.is(e.target) && button.has(e.target).length == 0 && 
       !container.is(e.target) && container.has(e.target).length == 0) {  
    reset_area();
    // $(button).blur();
  }
});

// активируем выбраный фмльтр 
$(document).on('click', '.filter-check', function(){
    $(this).toggleClass('filter-active');

    var filter_list = get_checked_filter();
    ui_prepare_filter();
    send_filter(filter_list);
});

function ui_prepare_filter() {
  var filter_list = get_checked_filter();
  display_filter_checked_count(filter_list);
  ui_display_filter_chips(filter_list);  
}

//получем массив всех активных фильтров на странице
function get_checked_filter() {
  var filter_list = [];
  var data = [];
  $('.checker-list').find('.filter-active').each(function(){
    let filter_name = $(this).closest('.area-container').find('.area-button').find('.widget__button-text').text().trim();
		let filter_id = $(this).attr('id');
    let filter_type = $(this).attr('filter-type');
    let value = $(this).find('.widget__button-text').text().trim();
    let mark = $(this).find('.widget__mark').text();
    filter_list.push({
      filter_id, 
      filter_type, 
      filter_name, 
      value, 
      mark
    });
  });
  
  return filter_list;
}

//счетчик активных фильтров
function display_filter_checked_count(filter_list) {
  $('.filter-container').find('.filter-count').html(filter_list.length);
}

function ui_display_filter_chips(list) {
  var filt_arr = [];

  $parent = $('.checked-filter-list');
  $child = $('.checked-mark-item');

  list.forEach(el => {

    //находим все выбранные фильтры и удаяем те, которых нет в массиве
    $parent.each(function(){
      if($(this).find($child).length) {
        var ardy_id =  $(this).find($child).data('rel-filter-id');
        //console.log(ardy_id);

        if(ardy_id !== el.filter_type) {
          $(this).find($child).remove();
        }
      }
    });
 
    if($parent.find(`.checked-mark-item[data-rel-filter-id="${el.filter_type}"]`).length === 0){
      $parent.append(`
        <div  class="checked-mark-item " data-rel-filter-id="${el.filter_type}" > 
          <div class="checked-mark-title">${el.filter_name}</div> 
          <div class="chips-list">
          
          </div> 
        </div>
      `);
    } 


    $this = $parent.find(`.checked-mark-item[data-rel-filter-id="${el.filter_type}"]`);
    if($this.find('.chips-list').find(`.checked-chips[data-filter-chip-id="${el.filter_id}"]`).length === 0) {
      $this.find('.chips-list').append(`
       <a class="checked-chips remove_checked_filter" data-filter-chip-id="${el.filter_id}" href="javascript:void(0)">
          <span class="checked-mark-value">${el.value} ${el.mark}</span>
          <span class="remove-checked-icon flex-cntr">
            <i class="las la-times"></i>
          </span>           
        </a> 
      `);
    }
  });


  if(!$('.checked-filter-list').children() || list.length == 0) {
    // alert('array is empty');  
    $('.checked-filter-list').empty();
  } 
}

$(document).on('click', '.remove_checked_filter', function(){
  $this = $(this);
  id = $this.data('filter-chip-id');

  $(`#${id}.filter-check.filter-active`).trigger('click');
});

function reset_all_filter() {
  $('.filter-check').removeClass('filter-active');
  ui_prepare_filter();
}

/** counter input - счетчик для инпута */
$('body').on('click', '.cart-counter', function(){
  var $input = $(this).parent().find('.cart-counter-input');
  let count = $input.val();

  if($(this).hasClass('cart-plus-count')) {
    count++;
  } 
  if($(this).hasClass('cart-minus-count')) {
    count--;
  }
  
  $input.val(count);
  $input.trigger('input').focus();
});

/** counter end */

/** widget end */

$('body').on('click', '.close_modal_btn, .overlay', function(){
  pageData.rightSideModalHide();
  pageData.overlayHide();
});


/** поля которые были изменены start */
  $('body').on('focusout keyup input click', '.edit', function() {
    $(this).addClass('edited');
  });
/** поля которые были изменены end */



$(document).on('click', '.select-hidden-fields-input', function() {
  var get_id = $(this).data('id');
  $(this).closest('.fields')
          .find('.hidden-fields-input')
          .val(get_id)
          .addClass('edited');
});


//открыть потверждение удаление товара
$(document).on('click', '.open-delete-stock-modal', function(){
  $('.fields-modal-container').fadeIn();
});

//закрыть потверждение удаление товара
$(document).on('click', '.cancle-fields-modal', function() {
  $('.fields-modal-container').fadeOut();
});


// dom live search
$(document).on('keyup', '.dom-live-search', function(){
  var get_value = $(this).val().toLowerCase();

  $(this).closest('.search-container').find('.search-list-content li').filter(function(){
    $(this).toggle($(this).find('.widget__button-text').text().trim().toLowerCase().indexOf(get_value) > -1);
  });
});


// собираем поля формы
function prepare_form_fields($this) {
  let prepare_data = {};
  $this.find('.add-stock').each(function(){
    if($(this).data('fields-name')) {
      var data_name = $(this).data('fields-name');
      var val = $(this).val();
      prepare_data[data_name] = val;
    }
  });

  return prepare_data;
}


/**
 * custom radio switcher
 */
 $(document).on('click', '.ls-switcher', function() {
  var get_radio_state = $(this).attr('data-radio-state');
  let set_radio_state;

  set_radio_state = get_radio_state == 0 ? 1 : 0;

  console.log(set_radio_state);
  $(this).attr('data-radio-state', set_radio_state).val(set_radio_state);
});