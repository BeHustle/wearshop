<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/dbconfig.php');

try {
    if (is_numeric($_POST['product_id'])) {
        if (getProductById($_POST['product_id']) !== false) {
            if (deleteProduct($_POST['product_id'])) {
                echo 'success';
            } else {
                throw new Exception('Товар не был удален');
            }
        } else {
            throw new Exception('Товар с таким ID не обнаружен в базе');
        }
    } else throw new Exception('Недопустимый id товара');
} catch (Exception $e) {
    echo 'Возникла ошибка! ' . $e->getMessage();
}
