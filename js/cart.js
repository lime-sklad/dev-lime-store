/** EXPERIMENTAL 4444444444444444444444*/

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
    const cart_id = cart.get_cart_item_id($this);
    const carts = cart.get_cart_list();
    carts.forEach(el => {
      if(el.id == cart_id) {
        if(preg_val && preg_val <= 0) {
          $this.val(1);
        }
        else if(preg_val > el.maxCount) {
          $this.val(el.maxCount);
        } else {
          $this.val(preg_val);
        }
      }
    });
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

  function alert_vlidate_notice(el) {
    hide_validate_notice(el);
    el.addClass('input-validate-error');
  }

  function hide_validate_notice(el) {
    el.removeClass('input-validate-error');
    el.parent().find('.warning-notice').remove();
  }
  
  var cart_list = [];
  
  cart = {
    draw: function() {
      console.log('draw')

      $('.cart-item').removeClass('in-cart');

      let isDone = false;

        cart_list.forEach(item => {
          $(`.cart-item[data-cart-id="${item.id}"]`).addClass('in-cart');

          if($(`.cart-item[data-cart-id="${item.id}"]`).length == 0) {
            isDone = false;
            if(isDone == false) {
              $.ajax({
                url: '/core/action/cart/cart_item_row.php',
                type: 'POST',
                data: {
                    items: item
                },
                success: function(data) {
                  cart.render(data);

                }
              });
            }
            isDone = true;

          } 
          isDone = true;

        });


        if(isDone) {
          console.log('delete', isDone);
          $('.cart-item').each(function(){
            if(!$(this).hasClass('in-cart')) {
              $(this).remove();
            }
          });
        }


    // });          
 
    //   $('.cart-item').each(function(){
    //     if(!$(this).hasClass('in-cart')) {
    //       // $(this).remove();
    //     }
      // });

      // if(!$('.cart-item').hasClass('in-cart')) {
      //  $('.cart-item').addClass('no-in-cart');
      // }

      // if(myArray.length > 0) {
      //   $.ajax({
      //     url: '/core/action/cart/cart_item_row.php',
      //     type: 'POST',
      //     data: {
      //         items: myArray
      //     },
      //     success: function(data) {
      //       myArray = [];
      //       cart.render(data);
      //     }
      //   });
      // }
    },
    render: function(data) {
      $('.cart').find('.cart-item-list').append(data);
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
    get_cart_item_id: function($this) {
      return $this.closest('.cart-item').data('cart-id');
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
    show_in_cart_count: function() {
      var $cart_mark = $('.in-cart-count');
      var cart_count = cart_list.length;
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
            cart: cart_list
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
      // let inner_val = $el.text().trim();
    
      // inner_val = parseFloat(inner_val);

      // let res = 0;
      // cart_list.forEach(el => {
      //   res += el.price * el.count; 
      // });

      // if(inner_val != res) {
      //   console.log({inner_val, res});
      //   $el.text(res);
      //   return true;
      // } 

      $el.text('333');
      // return false;
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
    console.log('dds');
  });
  
  $('body').on('click', '.remove-at-cart', function(){
    var id = cart.get_cart_item_id($(this));

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
    $input.trigger('input').focus();
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
      }
    });   
  });
  
  function init_obs() {
    var target = document.getElementById('app');
  
    const config = {
      childList: true,
      subtree: true,
      characterData: true,
    };
  
    const callback = function(mutationList, observer) {

      if($('.cart').length) {
        cart.draw();
        cart.display_total();
        console.log('2 raza')
      }       


      mutationList.forEach(el => {



          const config = {
            childList: true,
            subtree: true,
            // attributes: true,
            // attributeFilter: ['class']
          };          
  
          // console.log('new mut' + mutationList);

  
        // if(el.type == 'attributes') {
        //   if($('.table-list').length) {
        //     cart.active_all_btn();
        //   }
        //   // cart.show_in_cart_count();
        // }
      });
    };
    const observer = new MutationObserver(callback);
    observer.observe(target, config);    
  }

  init_obs();
});
/** END EXPERIMENTAL */