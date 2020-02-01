<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/pre_loader.php');

const GROUP_NAME = 'Администраторы';
const ITEMS_ON_PAGE = 5;

$access = false;
if (isset($_SESSION['user_id'])) {
    $access = getUserCategory($_SESSION['user_id']);
} else {
    $access = false;
}

if ($access) {
    $products_stmt = getProducts($_GET);
    $count_products = getCountProducts($_GET);
    $products = [];
    foreach ($products_stmt as $product) {
        $products[] = [
            'name' => $product['name'],
            'id' => $product['id'],
            'price' => $product['price'],
            'new' => $product['new'],
            'categories' => getCategoryByProductId($product['id'])
        ];
    }
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
<?php if ($access) : ?>
<main class="page-products">
  <h1 class="h h--1">Товары</h1>
  <a class="page-products__button button" href="/add">Добавить товар</a>
  <div class="page-products__header">
    <span class="page-products__header-field">Название товара</span>
    <span class="page-products__header-field">ID</span>
    <span class="page-products__header-field">Цена</span>
    <span class="page-products__header-field">Категория</span>
    <span class="page-products__header-field">Новинка</span>
  </div>
  <ul class="page-products__list">
      <?php foreach ($products as $product) : ?>
    <li class="product-item page-products__item" id="<?= $product['id'] ?>">
      <b class="product-item__name"><?= $product['name'] ?></b>
      <span class="product-item__field"><?= $product['id'] ?></span>
      <span class="product-item__field"><?= $product['price'] ?> руб.</span>
      <span class="product-item__field"><?php foreach ($product['categories'] as $category) {
          echo $category['name']; ?> <br> <?php
          } ?></span>
      <span class="product-item__field"><?= $product['new'] ?></span>
      <a href="/edit?id=<?= $product['id'] ?>" class="product-item__edit" aria-label="Редактировать"></a>
      <button class="product-item__delete"></button>
    </li>
    <?php endforeach ?>
  </ul>
    <br>
    <ul class="shop__paginator paginator">
        <?php for ($i = 1; $i <= ceil($count_products / ITEMS_ON_PAGE); $i++) : ?>
            <li>
                <a class="paginator__item"<?= ($_GET['page'] == $i) ? null : 'href="?' . http_build_query(array_merge($_GET, ['page' => $i])) . '"' ?>><?= $i ?></a>
            </li>
        <?php endfor ?>
    </ul>
</main>
<?php else: ?>
    <p>Ошибка доступа! У вас недостаточно прав на просмотр этой страницы</p>
<?php endif ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php') ?>
