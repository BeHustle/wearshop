<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/pre_loader.php');

$termination = ['модель', 'модели', 'моделей'];

const ITEMS_ON_PAGE = 5;
$price = getMinMaxPrice($_GET);
$count_products = getCountProducts($_GET);
$products = getProducts($_GET);
$categories = getCategories();

?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header_index.php') ?>
<main class="shop-page">
  <header class="intro">
    <div class="intro__wrapper">
      <h1 class=" intro__title">COATS</h1>
      <p class="intro__info">Collection 2018</p>
    </div>
  </header>
  <section class="shop container">
    <section class="shop__filter filter">
      <form>
      <div class="filter__wrapper">
        <b class="filter__title">Категории</b>
        <ul class="filter__list">
          <li>
            <a class="filter__list-item
            <?= ($_GET['cat'] == 'all') ? ' active"' : '" href="?' . http_build_query(array_merge($_GET, ['cat' => 'all'])) . '"'?>>Все</a>
          </li>
            <?php foreach ($categories as $category) :?>
                <li>
                    <a class="filter__list-item<?= ($_GET['cat'] == $category['id']) ? ' active"' : '" href="?' . http_build_query(array_merge($_GET, ['cat' => $category['id']])) . '"' ?>>
                    <?= $category['name'] ?></a>
                </li>
            <?php endforeach ?>
        </ul>
          <input type="hidden" name="cat" value="<?= isset($_GET['cat'])  ? htmlspecialchars($_GET['cat']) : 'all' ?>">
      </div>
        <div class="filter__wrapper">
          <b class="filter__title">Фильтры</b>
          <div class="filter__range range">
            <span class="range__info">Цена</span>
            <div class="range__line" aria-label="Range Line"></div>
            <div class="range__res">
              <span id="minimum" hidden><?= ($price['min_price']) ? ($price['min_price']) : 0 ?></span>
                <span class="range__res-item min-price"><?= isset($_GET['min_price']) ? $_GET['min_price'] . ' руб.' : $price['min_price'] . ' руб.' ?></span>
              <input type="hidden" id="min_price" name="min_price" value="<?= isset($_GET['min_price']) ? $_GET['min_price'] : $price['min_price'] ?>">
                <span id="maximum" hidden><?= ($price['max_price']) ? ($price['max_price']) : 0 ?></span>
                <span class="range__res-item max-price"><?= isset($_GET['max_price']) ? $_GET['max_price'] . ' руб.' : $price['max_price'] . ' руб.' ?></span>
                <input type="hidden" id="max_price" name="max_price" value="<?= isset($_GET['max_price']) ? $_GET['max_price'] : $price['max_price'] ?>">
            </div>
          </div>
        </div>

        <fieldset class="custom-form__group">
          <input type="checkbox" name="new" id="new" class="custom-form__checkbox" <?= isset($_GET['new']) ? 'checked' : null ?>>
          <label for="new" class="custom-form__checkbox-label custom-form__info" style="display: block;">Новинка</label>
          <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox" <?= isset($_GET['sale']) ? 'checked' : null ?>>
          <label for="sale" class="custom-form__checkbox-label custom-form__info" style="display: block;">Распродажа</label>
        </fieldset>
        <?php if ($_GET['sort']) : ?>
          <input type="hidden" name="sort" value="<?= $_GET['sort'] ?>">
        <?php endif ?>
          <button class="button" type="submit" style="width: 100%">Применить</button>
        </form>
        <br>
        <a class="button" style="width: 100%" href="/">Сбросить</a>
    </section>

    <div class="shop__wrapper">
        <p>Сортировка:</p>
      <section class="shop__sorting">
        <div class="shop__sorting-item custom-form__select-wrapper">
          <select class="custom-form__select" name="sort_price" onChange="window.location=this.value;">
            <option hidden="">По цене</option>
            <option value="?<?= http_build_query(array_merge($_GET, ['sort' => 'price_ASC'])) ?>">По возрастанию</option>
            <option value="?<?= http_build_query(array_merge($_GET, ['sort' => 'price_DESC'])) ?>">По убыванию</option>
          </select>
        </div>
        <div class="shop__sorting-item custom-form__select-wrapper">
          <select class="custom-form__select" name="sort_name" onChange="window.location=this.value;">
            <option hidden="">По имени</option>
            <option value="?<?= http_build_query(array_merge($_GET, ['sort' => 'name_ASC'])) ?>">От А до Я</option>
            <option value="?<?= http_build_query(array_merge($_GET, ['sort' => 'name_DESC'])) ?>">От Я до А</option>
          </select>
        </div>
        <p class="shop__sorting-res">Найдено <span class="res-sort"><?= declension($count_products, $termination) ?></span></p>
      </section>
      <section class="shop__list">
        <?php foreach ($products as $product) : ?>
          <article class="shop__item product"  tabindex="0">
          <div class="product__image">
            <img src="<?= $product['img_path'] ?>" alt="<?= $product['name'] ?>">
          </div>
          <p class="product__name" ><?= $product['name'] ?></p>
          <span class="product__price" ><?= $product['price'] ?> руб.</span>
          <span class="product_id" hidden><?= $product['id'] ?></span>
        </article>
          <?php endforeach ?>
      </section>
      <ul class="shop__paginator paginator">
          <?php for ($i = 1; $i <= ceil($count_products / ITEMS_ON_PAGE); $i++) : ?>
            <li>
                <a class="paginator__item"<?= ($_GET['page'] == $i) ? null : 'href="?' . http_build_query(array_merge($_GET, ['page' => $i])) . '"' ?>><?= $i ?></a>
            </li>
          <?php endfor ?>
      </ul>
    </div>
  </section>
  <section class="shop-page__order" hidden="">
    <div class="shop-page__wrapper">
      <h2 class="h h--1">Оформление заказа</h2>
        <p style="color: red" id="error_msg"></p>
        <legend class="custom-form__title">Ваш заказ:</legend>
        <br>
        <div class="page-products__header">
            <span class="page-products__header-field">Название товара</span>
            <span class="page-products__header-field">ID</span>
            <span class="page-products__header-field">Цена</span>
        </div>
        <ul class="page-products__list">
            <li class="product-item page-products__item">
                <b class="product-item__name"></b>
                <span class="product-item__field" id="span_product_id"></span>
                <span class="product-item__field" id="span_product_price"></span>
            </li>
        </ul>
        <br>
      <form action="/include/ajax/index_form.php" method="post" class="custom-form js-order">
          <input type="hidden" id="product_id" name="product_id" value="PRODUCT_ID">
        <fieldset class="custom-form__group">
          <legend class="custom-form__title">Укажите свои личные данные</legend>
          <p class="custom-form__info">
            <span class="req">*</span> поля обязательные для заполнения
          </p>
          <div class="custom-form__column">
            <label class="custom-form__input-wrapper" for="surname">
              <input id="surname" class="custom-form__input" type="text" name="surname" required="">
              <p class="custom-form__input-label">Фамилия <span class="req">*</span></p>
            </label>
            <label class="custom-form__input-wrapper" for="name">
              <input id="name" class="custom-form__input" type="text" name="name" required="">
              <p class="custom-form__input-label">Имя <span class="req">*</span></p>
            </label>
            <label class="custom-form__input-wrapper" for="thirdname">
              <input id="thirdname" class="custom-form__input" type="text" name="thirdname">
              <p class="custom-form__input-label">Отчество</p>
            </label>
            <label class="custom-form__input-wrapper" for="phone">
              <input id="phone" class="custom-form__input" type="tel" name="phone" required="">
              <p class="custom-form__input-label">Телефон <span class="req">*</span></p>
            </label>
            <label class="custom-form__input-wrapper" for="email">
              <input id="email" class="custom-form__input" type="email" name="email" required="">
              <p class="custom-form__input-label">Почта <span class="req">*</span></p>
            </label>
          </div>
        </fieldset>
        <fieldset class="custom-form__group js-radio">
          <legend class="custom-form__title custom-form__title--radio">Способ доставки</legend>
          <input id="dev-no" class="custom-form__radio" type="radio" name="delivery" value="dev-no" checked="">
          <label for="dev-no" class="custom-form__radio-label">Самовывоз</label>
          <input id="dev-yes" class="custom-form__radio" type="radio" name="delivery" value="dev-yes">
          <label for="dev-yes" class="custom-form__radio-label">Курьерная доставка</label>
        </fieldset>
        <div class="shop-page__delivery shop-page__delivery--no">
          <table class="custom-table">
            <caption class="custom-table__title">Пункт самовывоза</caption>
            <tr>
              <td class="custom-table__head">Адрес:</td>
              <td>Москва г, Тверская ул,<br> 4 Метро «Охотный ряд»</td>
            </tr>
            <tr>
              <td class="custom-table__head">Время работы:</td>
              <td>пн-вс 09:00-22:00</td>
            </tr>
            <tr>
              <td class="custom-table__head">Оплата:</td>
              <td>Наличными или банковской картой</td>
            </tr>
            <tr>
              <td class="custom-table__head">Срок доставки: </td>
              <td class="date">13 декабря—15 декабря</td>
            </tr>
          </table>
        </div>
        <div class="shop-page__delivery shop-page__delivery--yes" hidden="">
          <fieldset class="custom-form__group">
            <legend class="custom-form__title">Адрес</legend>
            <p class="custom-form__info">
              <span class="req">*</span> поля обязательные для заполнения
            </p>
            <div class="custom-form__row">
              <label class="custom-form__input-wrapper" for="city">
                <input id="city" class="custom-form__input" type="text" name="city">
                <p class="custom-form__input-label">Город <span class="req">*</span></p>
              </label>
              <label class="custom-form__input-wrapper" for="street">
                <input id="street" class="custom-form__input" type="text" name="street">
                <p class="custom-form__input-label">Улица <span class="req">*</span></p>
              </label>
              <label class="custom-form__input-wrapper" for="home">
                <input id="home" class="custom-form__input custom-form__input--small" type="text" name="home">
                <p class="custom-form__input-label">Дом <span class="req">*</span></p>
              </label>
              <label class="custom-form__input-wrapper" for="aprt">
                <input id="aprt" class="custom-form__input custom-form__input--small" type="text" name="aprt">
                <p class="custom-form__input-label">Квартира <span class="req">*</span></p>
              </label>
            </div>
          </fieldset>
        </div>
        <fieldset class="custom-form__group shop-page__pay">
          <legend class="custom-form__title custom-form__title--radio">Способ оплаты</legend>
          <input id="cash" class="custom-form__radio" type="radio" name="pay" value="cash">
          <label for="cash" class="custom-form__radio-label">Наличные</label>
          <input id="card" class="custom-form__radio" type="radio" name="pay" value="card" checked="">
          <label for="card" class="custom-form__radio-label">Банковской картой</label>
        </fieldset>
        <fieldset class="custom-form__group shop-page__comment">
          <legend class="custom-form__title custom-form__title--comment">Комментарии к заказу</legend>
          <textarea class="custom-form__textarea" name="comment"></textarea>
        </fieldset>
        <button class="button" type="submit">Отправить заказ</button>
      </form>
    </div>
  </section>
  <section class="shop-page__popup-end" hidden="">
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
      <h2 class="h h--1 h--icon shop-page__end-title">Спасибо за заказ!</h2>
      <p class="shop-page__end-message">Ваш заказ успешно оформлен, с вами свяжутся в ближайшее время</p>
      <button class="button">Продолжить покупки</button>
    </div>
  </section>
</main>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php') ?>
