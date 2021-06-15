/** GLOBAL START */
//показать индинтификатор страницы
$(document).ready(function(){
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
      }, 300);
    },
    rightSideModal: function(data) {
      var $modal_wrp = $('.module_fix_right_side');
      
      $modal_content = $modal_wrp.find('.modal_view_stock_order');
      $modal_wrp.removeClass('hide').addClass([
        'animate__animated',
        'animate__faster',
        'animate__slideInRight'
      ]);
      $modal_content.html(data);
    },
    rightSideModalHide: function() {
      $modal_wrp = $('.module_fix_right_side');
      
      $modal_wrp.removeClass([
        'animate__animated',
        'animate__slideInRight',
      ]).addClass('hide').find('.modal_view_stock_order').empty();
    },
    overlayShow: function() {
      $('.overlay').show();
    },  
    overlayHide: function() {
      $('.overlay').hide();
    },
    overlayToggle: function() {
      $('.overlay').toggle();
    }
  };
});

/** GLOBAL END  */

/** menu start */

//показывать бокове меню при наведении с задержкой в 700 мс
$(function () {
    let timeoutId = null;
    $(".sidebar").hover(
      function () {
        timeoutId = setTimeout(() => {
          $(this).addClass("sidebar_hovered");
        }, 780);
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
    'animate__slideInRight'
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
  $('.sidebar-list').removeClass('sidebar-active');
  $(`.sidebar-list[data-tab="${tab}"]`).addClass('sidebar-active');
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
    'animate__faster'
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

$(document).on('keydown keyup', '.search-auto', function(e){
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
      var node_selected = $('.selected');
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

        this_node[0].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' })
        
        ui_select_nav(this_node);
      }   
    }    
  } else {
    if(e.type == 'keyup') {
      send_autocomplete($(this));
    }
  }
});

$(document).on('click', '.selectable-search-item.selected', function(){
  var data_text = $(this).children('.widget__button-text').text().trim();
  var $parent = $(this).closest('.search-container');
  var $input = $parent.find('.search-auto');

  $input.val(data_text);
  send_autocomplete($input);;
  reset_area();
});
          
$(document).on('click', '.reset-search', function(){
  $this = $(this);
  var $parent = $this.closest('.search-container');
  var $input = $parent.find('.search-action');

  $input.val('');
  //костыль, исправить потом (что бы сбросить поиск, отправляем пустой массив фильтров которе возвращает дефолтноные данные)
  send_filter([]);
});

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
  if ( !button.is(e.target) && button.has(e.target).length === 0 && 
       !container.is(e.target) && container.has(e.target).length === 0) {   
         console.log('sd');
    reset_area();
    $(button).blur();
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

  $(`.filter-check.filter-active#${id}`).trigger('click');
});

function reset_all_filter() {
  $('.filter-check').removeClass('filter-active');
  ui_prepare_filter();
}



/** widget end */

$('body').on('click', '.close_modal_btn, .overlay', function(){
  pageData.rightSideModalHide();
  pageData.overlayHide();
});



/** EXPERIMENTAL */
$(document).ready(function(){


  $('body').on('focusout input', '.input-validate-length', function(){
    var val = $(this).val();
    input_validate_lenght(val, $(this));
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
    // var preg_val = preg_val.trim();
    const cart_id = $this.closest('.cart-item').data('cart-id');
    
    const carts = cart.get_cart_list();
    carts.forEach(el => {
      if(el.id == cart_id) {
        if(preg_val <= 0 || preg_val > el.maxCount) {
          alert_notice('Минимальное количество 1', $this);
        } else {
          hide_notice($this);
        }

        $this.val(preg_val);
      }
    });
  });

  function input_validate_lenght(val, $this) {
    val.trim().length == 0 ? alert_notice('Поле не может быть пустым!', $this) : hide_notice($this);
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

  function alert_notice(text, el) {
    hide_notice(el);
    el.addClass('input-validate-error');
  }

  function hide_notice(el) {
    el.removeClass('input-validate-error');
    el.parent().find('.warning-notice').remove();
  }


  var cart_list = [];

  cart = {
    draw: function() {
      var $wrp = $('.cart').find('.cart-item-list');
      $('.cart-item').removeClass('in-cart');

      cart_list.forEach(item => {
        $(`.cart-item[data-cart-id="${item.id}"]`).addClass('in-cart');

        if($(`.cart-item[data-cart-id="${item.id}"]`).length == 0) {
          $('.cart').find('.cart-item-list').append(`
            <tr class="cart-item in-cart" data-cart-id="${item.id}">
                <td>
                    <span class="stock-list-title">${item.id}</span>
                </td>

                <td>
                    <span class="stock-list-title">${item.name}</span>
                </td>

                <td>
                    <div class="cart-input-container flex flex-ai-cntr">
                        <div class="cart-input-icon cart-input-price-icon opacity-06">
                            <img src="../../img/icon/manat.svg">
                        </div>                                        
                        <input type="text" class="cart-order-price input cart-input input-validate-length input-validate-price input-required" value="">
                    </div>
                </td>

                <td>
                    <div class="flex flex-ai-cntr cart-input-container">
                        <button class="las la-minus btn btn-default add-basket-btn-icon cart-counter cart-minus-count"></button>
                          <div class="counter-input">
                            <input type="text" class="cart-order-count cart-order-input input cart-input input-validate-length input-validate-count input-required"  value="${item.count}">
                          </div>
                        <button class="las la-plus btn btn-default add-basket-btn-icon cart-counter cart-plus-count"></button>
                    </div>                                  
                </td>

                <td>
                    <button class="btn btn-danger add-basket-btn-icon las la-trash remove-at-cart"></button>                                
                </td>
            </tr>             
          `);
        }

      });
   
      $('.cart-item').each(function(){
        if(!$(this).hasClass('in-cart')) {
          $(this).remove();
        }
      });
    },
    prepare_data: function(data) {
      var row = data['param'];
      var stock_id = row['stock_id'];
      var stock_name = row['stock_name'];
      var stock_count = row['stock_count'];
  
      myData = {
        id: stock_id,
        name: stock_name,
        price: '',
        count: 1,
        maxCount: stock_count
      }
      return myData;
    },
    push_cart: function(data) {
      let isPush = true;
      var this_data = cart.prepare_data(data);
      
      cart_list.forEach(el => {
        if(el.id == this_data.id) {
          isPush = false;
          cart.add_count(this_data);
        }
      });

      if(isPush) {
        cart_list.push(this_data);    
      }
    },
    add_count: function(stock, count) {
      cart_list.forEach(el => {
        if(el.id == stock.id) {
          var index = cart_list.indexOf(el);
          if(count) {
            cart_list[index].count = count;
          } else {
            cart_list[index].count++;
          }
        }
      });
    },
    update_carts: function(id, param, data) {
      cart_list.forEach(el => {
        if(el.id == id) {
          var index = cart_list.indexOf(el);
          cart_list[index][param] = data;
        }
      });
    },
    get_id: function($this) {
      return $this.closest('.stock-list').attr('id');
    },
    remove_at_cart: function(ids) {
      cart_list.forEach(el => {
        if(el.id == ids) {
          var index = cart_list.indexOf(el);
          cart_list.splice(index, 1);
        }
      });
    },
    reset_cart: function() {
      cart_list = [];
    },
    active_basket_btn: function($this) {
      var class_list = [
        'la-cart-plus',
        'la-check',
        'btn-secondary',
        'btn-success',
        'add-to-cart',
        'added-to-cart',
      ];
    
      $this.toggleClass(class_list).closest('.stock-list').toggleClass('stock-added-in-cart');
    },
    active_all_btn: function() {
      if(cart_list.length == 0) {
        if($('.added-to-cart')) {
          cart.active_basket_btn($('.added-to-cart'));
          return;
        }
      }

      cart_list.forEach(el => {
        var $stock = $(`.stock-list#${el.id}`);

        if($stock) {
          var $button = $stock.find('.add-to-cart');
          if(!$button.hasClass('.added-to-cart')) {
            cart.active_basket_btn($button);
          }
        }      
      });
    },
    get_cart_list: () => {
      return cart_list;
    },
    show_in_cart_count: () => {
      var $cart_mark = $('.in-cart-count');
      var cart_count = cart_list.length;
      var this_count = $cart_mark.text().trim();

      if(cart_count != this_count) {
        $('.in-cart-count').html(cart_count);
        return;
      }
    },
    send_cart() {
      let carts = cart.get_cart_list();

      $('.cart').find('.cart-item-list').find('.cart-item').each(function(){
        const id = $(this).data('cart-id');
        const get_price = $(this).find('.cart-order-price').val();
        const get_count = $(this).find('.cart-order-count').val();
        cart.update_carts(id, 'price', get_price);
        cart.update_carts(id, 'count', get_count);
      });

      console.log(cart_list);
    }, 
    is_cart_prepared: function() {
      if($('.cart-item').length > 0) {

        $('.cart-input').trigger('input');

        $('.cart-input').each(function(){
          console.log($(this).calssList);
        });

        // $('.cart-input').each(function(){
        //   if($(this).hasClass('input-validate-error').length) {
        //     console.log('Ошибка! провертье правильность запполненых данных \n ')
        //   } else {
        //     console.log('Успех! Можно отправить на бэк \n ');
        //   }
        // });
      }
    },
    modal_cart_show: function() {
      $('.cart').toggleClass('hide');
    }
  };


$('body').on('click', '.add-basket-button', function() {
  cart.active_basket_btn($(this));
});

$('body').on('click', '.added-to-cart', function(){
  var id = cart.get_id($(this));
  cart.remove_at_cart(id);
});

$('body').on('click', '.send-cart', function(){
  if(cart.is_cart_prepared()) {
    cart.send_cart();
    console.log('ajax..');
  } else {
    alert_notice('заполните все поля', $(this));
  }
});

$('body').on('click', '.reset-cart', function(){
  cart.reset_cart();
  cart.active_all_btn();
  cart.draw(); 
});

$('body').on('click', '.remove-at-cart', function(){
  var id = $(this).closest('.cart-item').data('cart-id');
  
  var $stock = $(`.stock-list#${id}`).find('.added-to-cart');
  cart.active_basket_btn($stock);
  cart.remove_at_cart(id);
  cart.draw();  
});


$('body').on('click', '.cart-counter', function(){
  var $input = $(this).parent().find('.cart-order-count');
  let count = $input.val();
  if($(this).hasClass('cart-plus-count')) {
    count++;
  } 
  if($(this).hasClass('cart-minus-count')) {
    count--;
  }

  $input.val(count);
  $input.trigger('input');
});


  $('body').on('click', '.add-to-cart', function(){
    var barcode = $(this).closest('.stock-list').attr('id');
    $.ajax({
      url: '/core/action/get_barcode_product.php',
      type: 'POST',
      dataType: 'json',
      data: {
        id: barcode
      },
      success: (data) => {
        cart.push_cart(data);
        console.log(cart.get_cart_list());
      }
    });   
  });

  function observe_body(params) {
    // Выбираем целевой элемент
    var target = document.getElementById('app');

    // Конфигурация observer (за какими изменениями наблюдать)
    const config = {
      attributes: true,
      childList: true,
      subtree: true
    };

    // Колбэк-функция при срабатывании мутации
    const callback = function(mutationsList, observer) {
      if($('.table-list').length) {
        cart.active_all_btn();
      }
      
      cart.show_in_cart_count();

      mutationsList.forEach( (mutation) => {
        switch(mutation.type) {
          case 'childList':
              if($('.cart').length) {
                cart.draw();
                cart.is_cart_prepared();
              }
            break; 
        }
      });

      console.log(observer);
    };

    // Создаём экземпляр наблюдателя с указанной функцией колбэка
    const observer = new MutationObserver(callback);

    // Начинаем наблюдение за настроенными изменениями целевого элемента
    observer.observe(target, config);   
  }

  observe_body();


});


/** END EXPERIMENTAL */