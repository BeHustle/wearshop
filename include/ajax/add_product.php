<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/dbconfig.php');
const IMG_MAX_FILE_SIZE = 5000000;

if (!empty($_POST)) {
    try {
        if (empty($_POST['product-name'])) {
            throw new Exception('Поле имени продукта не заполнено!');
        }
        if (empty($_POST['product-price']) || !is_numeric($_POST['product-price'])) {
            throw new Exception('Некорректно заполнена стоимость товара');
        }
        if (@!in_array('on', $_POST['category'])) {
            throw new Exception('Нужно выбрать хотя бы одну категорию!');
        }
        if (empty($_FILES)) {
            throw new Exception('Нужно загрузить файл');
        }
        if ($_FILES['product-photo']['error'] !== 0) {
            throw new Exception('Произошла ошибка при загрузке файлов');
        }
        if (!in_array($_FILES['product-photo']['type'], ['image/jpeg', 'image/png'])) {
            throw new Exception('Неверное расширение картинки. Допускается только jpg и png');
        }
        if ($_FILES['product-photo']['size'] > IMG_MAX_FILE_SIZE) {
            throw new Exception('Допустимый размер изображения 5мб');
        }
        foreach ($_POST['category'] as $key => $value) {
            if ($value === 'on') {
                $categories[] = $key;
            }
        }
        $product = [
            'name' => $_POST['product-name'],
            'price' => $_POST['product-price'],
            'categories' => $categories,
            'new' => ($_POST['new'] === 'on') ? 'yes' : 'no',
            'sale' => ($_POST['sale'] === 'on') ? 'yes' : 'no',
            'photo' => $_FILES['product-photo']['tmp_name']
        ];
        if (addProduct($product) === true) {
            echo 'success';
        } else {
            throw new Exception($result);
        }
    } catch (Exception $e) {
        echo 'Возникла ошибка!' . $e->getMessage();
    }
} else {
    die('Данные не отправлены');
}
