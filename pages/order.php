<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/pre_loader.php');

const GROUP_NAME = 'Операторы';
const ITEMS_ON_PAGE = 2;

$access = false;
if (isset($_SESSION['user_id'])) {
    $access = getUserCategory($_SESSION['user_id']);
} else {
    $access = false;
}

if ($access) {
    $orders = getOrders($_GET);
    $count_orders = getCountOrders();
}


?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
<?php if ($access) : ?>
<main class="page-order">
  <h1 class="h h--1">Список заказов</h1>
  <ul class="page-order__list">
      <?php foreach ($orders as $order) : ?>
    <li class="order-item page-order__item">
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--id">
          <span class="order-item__title">Номер заказа</span>
          <span class="order-item__info order-item__info--id"><?= $order['id'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Сумма заказа</span>
            <?= $order['price'] ?> руб.
        </div>
        <button class="order-item__toggle"></button>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--margin">
          <span class="order-item__title">Заказчик</span>
          <span class="order-item__info"><?= $order['name'] . ' ' . $order['surname'] . ' ' . $order['patronymic'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Номер телефона</span>
          <span class="order-item__info">+<?= $order['phone'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ доставки</span>
          <span class="order-item__info"><?= $order['delivery_type'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ оплаты</span>
          <span class="order-item__info"><?= $order['payment_type'] ?></span>
        </div>
        <div class="order-item__group order-item__group--status">
          <span class="order-item__title">Статус заказа</span>
          <span class="order-item__info order-item__info--no" id="<?= $order['id'] ?>"><?= $order['status'] ?></span>
          <button class="order-item__btn">Изменить</button>
        </div>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Адрес доставки</span>
          <span class="order-item__info"><?= $order['address'] ?></span>
        </div>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Комментарий к заказу</span>
          <span class="order-item__info"><?= $order['comment'] ?></span>
        </div>
      </div>
    </li>
    <?php endforeach ?>
  </ul>
    <br>
    <ul class="shop__paginator paginator">
        <?php for ($i = 1; $i <= ceil($count_orders / ITEMS_ON_PAGE); $i++) : ?>
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
