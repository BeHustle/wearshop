<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/pre_loader.php');

const GROUP_NAME = 'Администраторы';

$access = false;
if (isset($_SESSION['user_id'])) {
    $access = getUserCategory($_SESSION['user_id']);
} else {
    $access = false;
}
if ($access) {
    $categories = getCategories();
}

define("IMG_MAX_FILE_SIZE", 5000000);
define("IMG_MAX_FILES_COUNT", 5);

$current_page = 'Добавление товара';



?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
<?php if ($access) : ?>

  <main class="page-add">
  <h1 class="h h--1">Добавление товара</h1>
  <form name="add_product" class="custom-form" method="post" id="ajax_form" action="/include/ajax/add_product.php">
    <p style="color: red" id="error_msg"></p>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
      <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
        <input type="text" class="custom-form__input" name="product-name" id="product-name" required>
        <p class="custom-form__input-label">
          Название товара
        </p>
      </label>
      <label for="product-price" class="custom-form__input-wrapper">
        <input type="text" class="custom-form__input" name="product-price" id="product-price" required>
        <p class="custom-form__input-label">
          Цена товара
        </p>
      </label>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
      <ul class="add-list">
        <li class="add-list__item add-list__item--add">
          <input type="file" name="product-photo" id="product-photo" hidden="">
          <label for="product-photo">Добавить фотографию</label>
        </li>
      </ul>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
      <legend class="page-add__small-title custom-form__title">Раздел</legend>
      <?php foreach ($categories as $category) :?>
          <input type="checkbox" class="custom-form__checkbox" name="category[<?= $category['id'] ?>]" id="category<?= $category['id'] ?>">
          <label for="category<?= $category['id'] ?>" class="custom-form__checkbox-label"><?= $category['name'] ?></label>
      <?php endforeach ?>
      <br><br>
        <legend class="page-add__small-title custom-form__title">Опции</legend>
        <input type="checkbox" name="new" id="new" class="custom-form__checkbox">
        <label for="new" class="custom-form__checkbox-label">Новинка</label>
        <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox">
        <label for="sale" class="custom-form__checkbox-label">Распродажа</label>
    </fieldset>
    <button class="button" type="submit">Добавить товар</button>
  </form>
  <section class="shop-page__popup-end page-add__popup-end" hidden="">
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
      <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно добавлен</h2>
    </div>
  </section>
</main>
<?php else: ?>
    <p>Ошибка доступа! У вас недостаточно прав на просмотр этой страницы</p>
<?php endif ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php') ?>
