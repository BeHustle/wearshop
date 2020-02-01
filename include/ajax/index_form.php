<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/dbconfig.php');

if (!empty($_POST)) {
    try {
        if (empty($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
            throw new Exception('Некорректный id продукта');
        }
        if (empty($_POST['surname'])) {
            throw new Exception('Вы не указали фамилию');
        }
        if (empty($_POST['name'])) {
            throw new Exception('Имя не может быть пустым');
        }
        if (empty($_POST['phone']) || !checkPhoneNumber($_POST['phone'])) {
            throw new Exception('Некорректно указан телефон');
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Введите действительный email адрес');
        }
        if ($_POST['delivery'] == 'dev-no') {
            $order['delivery_type'] = $_POST['delivery'];
        } elseif ($_POST['delivery'] == 'dev-yes') {
            if (empty($_POST['city'])) {
                throw new Exception('Не указан город доставки');
            }
            if (empty($_POST['street'])) {
                throw new Exception('Не указана улица доставки');
            }
            if (empty($_POST['home'])) {
                throw new Exception('Не указан дом доставки');
            }
            if (empty($_POST['aprt'])) {
                throw new Exception('Не указана квартира для доставки');
            }
            $order['delivery_type'] = $_POST['delivery'];
            $order['address']['home'] = htmlspecialchars($_POST['home']);
            $order['address']['street'] = htmlspecialchars($_POST['street']);
            $order['address']['city'] = htmlspecialchars($_POST['city']);
            $order['address']['aprt'] = htmlspecialchars($_POST['aprt']);
        } else {
            throw new Exception('Некорректные данные о способе доставке');
        }
        if (empty($_POST['pay']) || !in_array($_POST['pay'], ['cash', 'card'])) {
            throw new Exception('Некорректно указан способ оплаты');
        }
        if (!checkIdProduct($_POST['product_id'])) {
            throw new Exception('Данный продукт не обнаружен в базе. Возможно он закончился или был деактивирован');
        }
        if (!empty($_POST['comment'])) {
            $order['comment'] = htmlspecialchars($_POST['comment']);
        }
        if (!empty($_POST['thirdname'])) {
            $order['thirdname'] = htmlspecialchars($_POST['thirdname']);
        }
        $order['payment_type'] = htmlspecialchars($_POST['pay']);
        $order['email'] = htmlspecialchars($_POST['email']);
        $order['phone'] = checkPhoneNumber(htmlspecialchars($_POST['phone']));
        $order['name'] = htmlspecialchars($_POST['name']);
        $order['surname'] = htmlspecialchars($_POST['surname']);
        $order['product_id'] = $_POST['product_id'];
        if (!newOrder($order)) {
            throw new Exception('Возникла ошибка в работе с базой данных');
        }
        echo 'success';
    } catch (Exception $e) {
        echo 'Возникла ошибка! ' . $e->getMessage();
    }
}
