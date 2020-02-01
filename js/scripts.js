'use strict';

const toggleHidden = (...fields) => {

  fields.forEach((field) => {

    if (field.hidden === true) {

      field.hidden = false;

    } else {

      field.hidden = true;

    }
  });
};

const labelHidden = (form) => {

  form.addEventListener('focusout', (evt) => {

    const field = evt.target;
    const label = field.nextElementSibling;

    if (field.tagName === 'INPUT' && field.value && label) {

      label.hidden = true;

    } else if (label) {

      label.hidden = false;

    }
  });
};

const toggleDelivery = (elem) => {

  const delivery = elem.querySelector('.js-radio');
  const deliveryYes = elem.querySelector('.shop-page__delivery--yes');
  const deliveryNo = elem.querySelector('.shop-page__delivery--no');
  const fields = deliveryYes.querySelectorAll('.custom-form__input');

  delivery.addEventListener('change', (evt) => {

    if (evt.target.id === 'dev-no') {

      fields.forEach(inp => {
        if (inp.required === true) {
          inp.required = false;
        }
      });


      toggleHidden(deliveryYes, deliveryNo);

      deliveryNo.classList.add('fade');
      setTimeout(() => {
        deliveryNo.classList.remove('fade');
      }, 1000);

    } else {

      fields.forEach(inp => {
        if (inp.required === false) {
          inp.required = true;
        }
      });

      toggleHidden(deliveryYes, deliveryNo);

      deliveryYes.classList.add('fade');
      setTimeout(() => {
        deliveryYes.classList.remove('fade');
      }, 1000);
    }
  });
};

const filterWrapper = document.querySelector('.filter__list');
if (filterWrapper) {

  filterWrapper.addEventListener('click', evt => {

    const filterList = filterWrapper.querySelectorAll('.filter__list-item');

    filterList.forEach(filter => {

      if (filter.classList.contains('active')) {

        filter.classList.remove('active');

      }

    });

    const filter = evt.target;

    filter.classList.add('active');

  });

}



const shopList = document.querySelector('.shop__list');
if (shopList) {

  shopList.addEventListener('click', (evt) => {

      //передаем значения для оформления заказа
      document.querySelector('.product-item__name').innerText = evt.target.querySelector('.product__name').innerText
      document.getElementById('product_id').value = evt.target.querySelector('.product_id').innerText
      document.getElementById('span_product_id').innerText = evt.target.querySelector('.product_id').innerText
      document.getElementById('span_product_price').innerText = evt.target.querySelector('.product__price').innerText
      //

      const prod = evt.path || (evt.composedPath && evt.composedPath());;
    if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item'))) {

      const shopOrder = document.querySelector('.shop-page__order');

      toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

      window.scroll(0, 0);
      shopOrder.classList.add('fade');
      setTimeout(() => shopOrder.classList.remove('fade'), 1000);

      const form = shopOrder.querySelector('.custom-form');
      labelHidden(form);

      toggleDelivery(shopOrder);

      const buttonOrder = shopOrder.querySelector('.button');
      const popupEnd = document.querySelector('.shop-page__popup-end');

      buttonOrder.addEventListener('click', (evt) => {

        form.noValidate = true;

        const inputs = Array.from(shopOrder.querySelectorAll('[required]'));

        inputs.forEach(inp => {

          if (!!inp.value) {

            if (inp.classList.contains('custom-form__input--error')) {
              inp.classList.remove('custom-form__input--error');
            }

          } else {

            inp.classList.add('custom-form__input--error');

          }
        });

        //

        //

        if (inputs.every(inp => !!inp.value)) {
          evt.preventDefault();
            $.ajax({
                type: $(form).attr('method'),
                url: $(form).attr('action'),
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(result) {
                    if (result === 'success') {
                        toggleHidden(shopOrder, popupEnd);

                        popupEnd.classList.add('fade');
                        setTimeout(() => popupEnd.classList.remove('fade'), 1000);

                        window.scroll(0, 0);

                        const buttonEnd = popupEnd.querySelector('.button');

                        buttonEnd.addEventListener('click', () => {


                            popupEnd.classList.add('fade-reverse');

                            setTimeout(() => {

                                popupEnd.classList.remove('fade-reverse');

                                toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));

                            }, 1000);

                        });
                    } else {
                        document.getElementById('error_msg').textContent = result;
                        window.scroll(0, 0);
                        evt.preventDefault();
                    }
                },
            });

        } else {
          window.scroll(0, 0);
          evt.preventDefault();
        }
      });
    }

  });

}


const pageOrderList = document.querySelector('.page-order__list');
if (pageOrderList) {

  pageOrderList.addEventListener('click', evt => {

    if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
      var path = evt.path || (evt.composedPath && evt.composedPath());
      Array.from(path).forEach(element => {

        if (element.classList && element.classList.contains('page-order__item')) {

          element.classList.toggle('order-item--active');

        }

      });

      evt.target.classList.toggle('order-item__toggle--active');

    }

    if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {

      const status = evt.target.previousElementSibling;

      if (status.classList && status.classList.contains('order-item__info--no')) {
        status.textContent = 'Обработан';
      } else {
        status.textContent = 'Не обработан';
      }

      status.classList.toggle('order-item__info--no');
      status.classList.toggle('order-item__info--yes');
      if(status.textContent === 'Обработан') {
          $.ajax({
              type: 'POST',
              url: '/include/ajax/order_status_edit.php',
              data: {order_status: status.textContent, order_id: status.id},
              cache: false,
              success: function(result){
                  if (result !== 'success') {
                      alert(result)
                  }
              }
          })
      } else if (status.textContent === 'Не обработан') {
          $.ajax({
              type: 'POST',
              url: '/include/ajax/order_status_edit.php',
              data: {order_status: status.textContent, order_id: status.id},
              cache: false,
              success: function(result){
                  if (result !== 'success') {
                      alert(result)
                  }
              }
          })
      }

    }

  });

}

const checkList = (list, btn) => {

  if (list.children.length === 1) {

    btn.hidden = false;

  } else {
    btn.hidden = true;
  }

};
const addList = document.querySelector('.add-list');
if (addList) {

  const form = document.querySelector('.custom-form');
  labelHidden(form);

  const addButton = addList.querySelector('.add-list__item--add');
  const addInput = addList.querySelector('#product-photo');

  checkList(addList, addButton);

  addInput.addEventListener('change', evt => {

    const template = document.createElement('LI');
    const img = document.createElement('IMG');

    template.className = 'add-list__item add-list__item--active';
    template.addEventListener('click', evt => {
      addList.removeChild(evt.target);
      addInput.value = '';
      checkList(addList, addButton);
    });

    const file = evt.target.files[0];
    const reader = new FileReader();

    reader.onload = (evt) => {
      img.src = evt.target.result;
      template.appendChild(img);
      addList.appendChild(template);
      checkList(addList, addButton);
    };

    reader.readAsDataURL(file);

  });

  const button = document.querySelector('.button');
  const popupEnd = document.querySelector('.page-add__popup-end');



  button.addEventListener('click', (evt) => {
      form.noValidate = true;
      const inputs = Array.from(form.querySelectorAll('[required]'));
      inputs.forEach(inp => {

          if (!!inp.value) {

              if (inp.classList.contains('custom-form__input--error')) {
                  inp.classList.remove('custom-form__input--error');
              }

          } else {

              inp.classList.add('custom-form__input--error');

          }
      });
    evt.preventDefault();
    if (inputs.every(inp => !!inp.value)) {
        $.ajax({
            type: $(form).attr('method'),
            url: $(form).attr('action'),
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                if (result === 'success') {
                    form.hidden = true;
                    popupEnd.hidden = false;
                } else {
                    document.getElementById('error_msg').textContent = result;
                    window.scroll(0, 0);
                    evt.preventDefault();
                }
            },
        });
    } else {
        window.scroll(0, 0);
        evt.preventDefault();
    }
  })

}

const productsList = document.querySelector('.page-products__list');
if (productsList) {

  productsList.addEventListener('click', evt => {

    const target = evt.target;
    if (target.classList && target.classList.contains('product-item__delete')) {
      var toDelete = confirm('Вы действительно хотите удалить товар?')
        if (toDelete) {
            $.ajax({
              type: 'POST',
              url: '/include/ajax/product_delete.php',
              data: {product_id : target.parentElement.id},
              cache: false,
              success: function(result){
                if (result === 'success') {
                    productsList.removeChild(target.parentElement)
                } else {
                  alert(result)
                }
              }
          })
        }
    }

  });

}

// jquery range maxmin
if (document.querySelector('.shop-page')) {
    const current_min = document.getElementById("min_price").value;
    const current_max = document.getElementById("max_price").value;
    const abs_min = parseInt(document.getElementById("minimum").innerText);
    const abs_max = parseInt(document.getElementById("maximum").innerText);
  $('.range__line').slider({
    min: abs_min,
    max: abs_max,
    values: [current_min, current_max],
    range: true,
    stop: function(event, ui) {

      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
      $('#min_price').val($('.range__line').slider('values', 0));
      $('#max_price').val($('.range__line').slider('values', 1));
    },
    slide: function(event, ui) {

      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
      $('#min_price').val($('.range__line').slider('values', 0));
      $('#max_price').val($('.range__line').slider('values', 1));
    },
  });

}

