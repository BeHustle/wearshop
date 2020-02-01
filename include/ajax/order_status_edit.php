<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/dbconfig.php');

try {
    if (empty($_POST['order_id']) && empty($_POST['order_status'])) {
        throw new Exception('Не указан номер и статус заказа');
    }

    if (!is_numeric($_POST['order_id'])) {
        throw new Exception('Неверный номер заказа');
    }
    if (!in_array($_POST['order_status'], ['Обработан', 'Не обработан'])) {
        throw new Exception('Неверно указан статус');
    }
    if (!getOrderById($_POST['order_id'])) {
        throw new Exception('Заказ не найден в базе');
    }
    if (changeOrderStatus($_POST['order_id'], $_POST['order_status'])) {
        echo 'success';
    } else {
        throw new Exception('Ошибка при работе с базой данных');
    }
} catch (Exception $e){
    echo 'Произошла ошибка при обработке заказа: ' . $e->getMessage() . ' статус заказа не изменен';
}