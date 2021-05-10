/** GLOBAL START */
//показать индинтификатор страницы
function get_page_param(attr) {
	var data_page = $('.stock_list_tbody').attr(attr);
	return data_page;
}

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

//открываем меню
function visible_menu(param) {
  var class_list = [
    'menu--active',
    'animate__animated',
    'animate__fast', 
    'animate__slideInDown'
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

function preloader_state(state, $append_to) {
  var $preloder = $('.body_prelodaer').find('.preloader');
  if(state == 'show') {
    $preloder.removeClass('hide').addClass('flex-cntr');
  }
  if(state == 'hide') {
    $preloder.addClass('hide').removeClass('flex-cntr');
  }
}

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
    // 'animate__lsFadeIn25',
    // 'animate__faster'
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
        ui_select_nav(node.children(children_element));
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
 
    if($parent.find(`.checked-mark-item[data-rel-filter-id="${el.filter_type}"]`).length <= 0){
      $parent.append(`
        <div  class="checked-mark-item " data-rel-filter-id="${el.filter_type}" > 
          <div class="checked-mark-title">${el.filter_name}</div> 
          <div class="chips-list">
          
          </div> 
        </div>
      `);
    } 


    $this = $parent.find(`.checked-mark-item[data-rel-filter-id="${el.filter_type}"]`);
    if($this.find('.chips-list').find(`.checked-chips[data-filter-chip-id="${el.filter_id}"]`).length <= 0) {
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


  if($('.checked-filter-list').children().length <= 0 || list.length <= 0) {
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

