/** EXPERIMENTAL */

$(document).ready(function(){

  cart = {
    //** settings */
    cart_list: [],
    is_draw_possible: true,
    /** end settings */

    draw: function() {
      $('.cart-item').removeClass('in-cart');

      let item_list = [];

      cart.cart_list.map(item => {
        $(`.cart-item[data-cart-id="${item.id}"]`).addClass('in-cart');

        if(!$(`.cart-item[data-cart-id="${item.id}"]`).length > 0) {
          cart.is_draw_possible = false;
          item_list.push(item);
        }           
      });

      if(item_list.length > 0) {
        $.ajax({
          url: '/core/action/cart/cart_item_row.php',
          type: 'POST',
          data: {
            items: item_list
          },
          success: function(data) {
            cart.render(data);

            console.log(data);
          }
        });    
      }

      $('.cart-item').each(function() {
        if(!$(this).hasClass('in-cart')) {
          $(this).remove();
        }
      });
    },
    render: function(data) {
      $('.cart').find('.cart-item-list').prepend(data);
      cart.is_draw_possible = true;
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
      
      cart.cart_list.forEach(el => {
        if(el.id == this_data.id) {
          isPush = false;
          cart.add_count(this_data);
        }
      });

      if(isPush) {
        cart.cart_list.push(this_data);    
      }
    },
    request_data(id) {
      $.ajax({
        url: '/core/action/stock/get_barcode_product.php',
        type: 'POST',
        dataType: 'json',
        data: {
          id: id
        },
        success: (data) => {
          console.log(data);
          cart.push_cart(data);
        }
      });
    },
    add_count: function(stock, count) {
      cart.cart_list.forEach(el => {
        if(el.id == stock.id) {
          var index = cart.cart_list.indexOf(el);
          if(count) {
            cart.cart_list[index].count = count;
          } else {
            cart.cart_list[index].count++;
          }
        }
      });
    },
    update_carts: function(id, param, data) {
      cart.cart_list.forEach(el => {
        if(el.id == id) {
          var index = cart.cart_list.indexOf(el);
          cart.cart_list[index][param] = data;
        }
      });
    },
    get_id: function($this) {
      return $this.closest('.stock-list').attr('id');
    },
    get_cart_item_id: function($this) {
      return $this.closest('.cart-item').data('cart-id');
    },
    remove_at_cart: function(ids) {
      cart.cart_list.forEach(el => {
        if(el.id == ids) {
          var index = cart.cart_list.indexOf(el);
          cart.cart_list.splice(index, 1);
        }
      });
    },
    reset_cart: function() {
      cart.cart_list = [];
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
      if(cart.cart_list.length == 0) {
        if($('.added-to-cart')) {
          cart.active_basket_btn($('.added-to-cart'));
          return;
        }
      }

      cart.cart_list.forEach(el => {
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
      return cart.cart_list;
    },
    show_in_cart_count: function() {
      var $cart_mark = $('.in-cart-count');
      var cart_count = cart.cart_list.length;
      var this_count = $cart_mark.text().trim();

      if(cart_count != this_count) {
        $cart_mark.html(cart_count);
      }
    },
    send_cart: () => {
      if(cart.is_cart_prepared()) {
        $.ajax({
          url: '/core/action/cart/checkout.php',
          type: 'POST',
          dataType: 'json',
          data: {
            cart: cart.cart_list
          },
          success: (data) => {
            if(data.notice.type == 'success') {
              cart.order_success();
            }
            pageData.alert_notice(data.notice.type, data.notice.text);
            console.log(data);
          }
        });
      }
    },
    display_total: () => {
      var $el = $('.cart-res-sum');
      let inner_val = $el.text().trim();
    
      inner_val = parseFloat(inner_val);

      let res = 0;
      cart.cart_list.forEach(el => {
        res += el.price * el.count; 
      });

      if(inner_val != res) {
        console.log({inner_val, res});
        $el.text(res);
        return true;
      } 

      return false;
    },
    is_cart_prepared: function() {
      $('.cart-input').trigger('input', 'keyup');
      if($('.input-validate-error').length) {
        pageData.alert_notice('error', 'заполните все поля');
        return false;
      }
      return true;
    },
    modal_cart_show: function() {
      $('.cart').toggleClass('hide');
    },
    order_success: function() {
      cart.reset_cart();
    }
  };


  $('body').on('focusout keyup input', '.input-validate-min-max-count', function(){
    const cart_id = cart.get_cart_item_id($(this));
    const carts = cart.get_cart_list();
  
    carts.forEach(el => {
      if(el.id == cart_id) {
        return input_validate_min_max_count(1, el.maxCount, $(this));
      }
    });
  
  });
  
  
  $('body').on('click', '.add-basket-button', function() {
    cart.active_basket_btn($(this));
  });
  
  $('body').on('click', '.added-to-cart', function(){
    var id = cart.get_id($(this));
    cart.remove_at_cart(id);
  });
  
  $('body').on('click', '.send-cart', function(){
    cart.send_cart(); 
  });
  
  $('body').on('click', '.reset-cart', function(){
    cart.reset_cart();
    cart.active_all_btn();
    cart.show_in_cart_count();
  });
  
  $('body').on('click', '.remove-at-cart', function(){
    var id = cart.get_cart_item_id($(this));

    var $stock = $(`.stock-list#${id}`).find('.added-to-cart');
    cart.active_basket_btn($stock);
    cart.remove_at_cart(id);
    cart.show_in_cart_count();
  });
  
    
  $('body').on('keyup', '.cart-order-price', function(){
    var id = cart.get_cart_item_id($(this));
    var val = $(this).val().trim();
  
    cart.update_carts(id, 'price', val);
    cart.display_total();
  });
  
  $('body').on('keyup input', '.cart-order-count', function(){
    var id = cart.get_cart_item_id($(this));
    var val = $(this).val().trim();
  
    cart.update_carts(id, 'count', val);
    cart.display_total();
  });
  
  $('body').on('click', '.add-to-cart', function(){
    var id = $(this).closest('.stock-list').attr('id');
    cart.request_data(id);
  });
  
  function init_obs() {
    console.log('obs was init');
    var target = document.getElementById('app');
  
    const config = {
      childList: true,
      subtree: true,
      attributes: true
    };
  
    const callback = function(mutationList, observer) {
      mutationList.forEach(el => {

        if($('.cart').length) {
          if(el.type == 'childList') {
              if(cart.is_draw_possible) {
                cart.draw();
              }
            
            cart.display_total();
          }
        } 

        console.log(cart.is_draw_possible);

        // console.log('new mut' + mutationList);
        if(el.type == 'attributes') {
          if($('.table-list').length) {
            cart.active_all_btn();
          }
          cart.show_in_cart_count();
        }
      });
    };
    const observer = new MutationObserver(callback);
    observer.observe(target, config);    
  }

  init_obs();

  $(document).pos();

  $(document).on('scan.pos.barcode', function(event){
    if($('.cart').length > 0) {
      var barcode = event.code;
      $.ajax({
        url: '/core/action/barcode/get_product_data.php',
        type: 'POST',
        dataType: 'json',
        data: {
        id: barcode
        },
        success: (data) => {
        cart.request_data(data.res_id);
        cart.display_total();
        }
      }); 
    }
  });
});
/** END EXPERIMENTAL */