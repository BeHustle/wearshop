<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/pre_loader.php');

$current_page = 'Администратор';

if (isset($_SESSION['user_id'])) {
    $order_assess = getUserCategory($_SESSION['user_id'], 'Операторы');
} else {
    $order_access = false;
}

if (isset($_SESSION['user_id'])) {
    $products_access = getUserCategory($_SESSION['user_id'], 'Администраторы');
} else {
    $products_access = false;
}



?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php') ?>
<main class="page-authorization">
    <?php if ($is_auth) : ?>

        <?php if ($order_assess) : ?><a class="button" href="/order">Панель заказов</a><?php endif ?>
        <?php if ($products_access) : ?><a class="button" href="/products">Каталог товаров</a><?php endif ?>
        <br>
        <a href="/admin?logout=yes">Выйти</a>
        <?php if (isset($result) && $result) {
            echo 'Вы успешно авторизовались!';
        }
        ?>
    <?php endif;
    if (!$is_auth) : ?>
  <h1 class="h h--1">Авторизация</h1>
    <?php
    if (isset($result) && !$result) {
        echo 'Неверные логин или пароль!';
    }
    ?>
  <form class="custom-form" action="/admin" method="post"> <!--= //$_SERVER['PHP_SELF'] ?-->
      <?php if (isset($_COOKIE['login'])) : ?>
      <input type="hidden" name="login" value="<?= htmlspecialchars($_COOKIE['login']) ?>">
      <?= htmlspecialchars($_COOKIE['login']) ?>
      <br><a href="/admin?login=no">Сменить пользователя</a><br />
      <?php else : ?>
    <input type="email" name="login" class="custom-form__input" required value="<?= $error ? $login : null ?>">
      <?php endif ?>
    <input type="password" name="password" class="custom-form__input" required value="<?= $error ? $password : null ?>">
    <button class="button" type="submit">Войти в личный кабинет</button>
  </form>
    <?php endif ?>
</main>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php') ?>
