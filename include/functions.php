<?php

function declension ($number, $array) {
    $case = [2, 0, 1, 1, 1, 2];
    return $number.' '.$array[ ($number % 100 > 4 && $number % 100 < 20) ? 2 : $case[min($number % 10, 5)] ];
}

function dbaseConnect()
{
    static $connect;
    if ($connect === null) {
        try {
            $connect = new PDO(DSN, USER, PASS, OPT);
        } catch (PDOException $e) {
            die ('Подключение не удалось' . $e->getMessage());
        }
    }
    return $connect;
}

function checkUser($login, $password) {
    $stmt = dbaseConnect()->prepare('SELECT id, password FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $login]);
    $result = $stmt->fetch();
    if ($result['id'] && $result['password'] === md5($password)) {
        return $result['id'];
    } else {
        return false;
    }
}

function menuList($current_page)
{
    $menu_items = dbaseConnect()->query('SELECT name, path from menu_items;');
    foreach ($menu_items as $item) {
        include ('menu_item.php');
    }
}

function getCurrentPage($get_page, $array_menu)
{
    if (!empty($get_page)) {
        foreach ($array_menu as $value) {
            if (stristr($value['path'], $get_page)) {
                return $value['title'];
            }
        }
    }
}

function getCategories()
{
    return dbaseConnect()->query('SELECT id, name FROM categories');
}

function constructQueryForProducts($query_array)
{
    $query = " FROM products as p WHERE p.is_active = 'yes'";
    if (empty($query_array)) {
        return $query;
    }
    if (isset($query_array['cat']) && $query_array['cat'] != 'all') {
        $query = " FROM products as p JOIN category_product as cp ON p.id = cp.product_id WHERE p.is_active = 'yes' AND cp.category_id = :cat_id";
    }
    if (isset($query_array['new']) && $query_array['new'] = 'on') {
        $query .= " AND p.new = 'yes'";
    }
    if (isset($query_array['sale']) && $query_array['sale'] = 'on') {
        $query .= " AND p.sale = 'yes'";
    }
    if (isset($query_array['min_price'])) {
        $query .= " AND p.price >= :min_price";
    }
    if (isset($query_array['max_price'])) {
        $query .= " AND p.price <= :max_price";
    }
    return $query;
}

function execArray($query_array)
{
    if (isset($query_array['cat']) && $query_array['cat'] != 'all') {
        $array['cat_id'] = $query_array['cat'];
    }
    if (isset($query_array['min_price'])) {
        $array['min_price'] = $query_array['min_price'];
    }
    if (isset($query_array['max_price'])) {
        $array['max_price'] = $query_array['max_price'];
    }
    if (isset($array)) {
        return $array;
    } else {
        return null;
    }
}

function getCountProducts($query_array)
{
    $query = "SELECT COUNT(p.id) as count" . constructQueryForProducts($query_array);
    $stmt = dbaseConnect()->prepare($query);
    $stmt->execute(execArray($query_array));
    return $stmt->fetch()['count'];
}

function getProducts($query_array)
{
    if (isset($query_array['sort'])) {
        $sort_array = explode('_', $query_array['sort']);
        if (in_array($sort_array[0], ['price', 'name'])) {
            $sort = 'p.' . $sort_array[0];
        }
        if (in_array($sort_array[1], ['ASC', 'DESC'])) {
            $order = $sort_array[1];
        } else {
            $sort = 'p.date_create';
            $order = 'ASC';
        }
    } else {
        $sort = 'p.date_create';
        $order = 'ASC';
    }
    $query = "SELECT p.id, p.name, p.price, p.img_path, p.new" . constructQueryForProducts($query_array) . " ORDER BY " . $sort . ' ' . $order . " LIMIT " . ITEMS_ON_PAGE . " OFFSET :offset";
    $stmt = dbaseConnect()->prepare($query);
    $array = execArray($query_array);
    if (isset($query_array['page']) && $query_array['page'] > 0) {
        $array['offset'] = ($query_array['page'] - 1) * ITEMS_ON_PAGE;
    } else {
        $array['offset'] = 0 * ITEMS_ON_PAGE;
    }
    $stmt->execute($array);
    return $stmt;
}

function getMinMaxPrice($query_array)
{
    if (isset($query_array['min_price'])) {
        unset ($query_array['min_price']);
    }
    if (isset($query_array['max_price'])) {
        unset ($query_array['max_price']);
    }
    $min_price = "SELECT MIN(p.price) as min_price" . constructQueryForProducts($query_array);
    $max_price = "SELECT MAX(p.price) as max_price" . constructQueryForProducts($query_array);
    $stmt = dbaseConnect()->prepare($min_price);
    $stmt->execute(execArray($query_array));
    $price['min_price'] = $stmt->fetch()['min_price'];
    $stmt = dbaseConnect()->prepare($max_price);
    $stmt->execute(execArray($query_array));
    $price['max_price'] = $stmt->fetch()['max_price'];
    return $price;
}

function getUserCategory($user_id, $group_name = GROUP_NAME)
{
    $stmt = dbaseConnect()->prepare('
    SELECT group_id FROM group_user AS gu JOIN groups AS g ON gu.group_id = g.id
    WHERE gu.user_id = :user_id AND g.name = :group_name LIMIT 1;');
    $stmt->execute(['user_id' => $user_id, 'group_name' => $group_name]);
    $result = $stmt->fetch();
    if ($result['group_id'] != null) {
        return true;
    } else {
        return false;
    }
}

function getCategoryByProductId($product_id)
{
    $stmt = dbaseConnect()->prepare('SELECT c.name as name, c.id  FROM categories as c JOIN category_product AS cp ON c.id = cp.category_id WHERE cp.product_id = :product_id ORDER BY c.id');
    $stmt->execute(['product_id' => $product_id]);
    return $stmt->fetchAll();
}

function getOrders($query)
{
    if (isset($query['page']) && $query['page'] > 0) {
        $offset = ($query['page'] - 1) * ITEMS_ON_PAGE;
    } else {
        $offset = 0 * ITEMS_ON_PAGE;
    }
    $stmt = dbaseConnect()->prepare('
    SELECT o.id, o.address, o.delivery_type, o.payment_type, o.comment, o.status, c.name, c.surname, c.thirdname, c.phone, p.price 
    FROM orders as o JOIN clients as c ON o.client_id = c.id JOIN products as p ON o.product_id = p.id ORDER BY o.date_create ASC LIMIT ' . ITEMS_ON_PAGE . ' OFFSET :offset;');
    $stmt->execute(['offset' => $offset]);
    return $stmt;
}

function getProductById($id)
{
    $stmt = dbaseConnect()->prepare('SELECT id, name, price, img_path, new, sale FROM products WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getCountOrders()
{
    $stmt = dbaseConnect()->query('SELECT COUNT(id) as c_id FROM ORDERS');
    return $stmt->fetch()['c_id'];

}

function checkPhoneNumber($phoneNumber)
{
    $phoneNumber = preg_replace('/\s|\+|-|\(|\)/','', $phoneNumber);
    if(is_numeric($phoneNumber))
    {
        if(mb_strlen($phoneNumber) < 5 || mb_strlen($phoneNumber) > 13)
        {
            return false;
        }
        else
        {
            return $phoneNumber;
        }
    }
    else
    {
        return false;
    }
}

function checkIdProduct($id)
{
    $stmt = dbaseConnect()->prepare("SELECT id FROM products WHERE id = ? AND is_active = 'yes'");
    $stmt->execute([$id]);
    if ($stmt->fetch()) {
        return true;
    } else {
        return false;
    }
}

function newOrder(array $order)
{
    if ($client = isOldClient($order['phone'], $order['email'])) {
        foreach ($client as $client_data) {
            $client_id = $client_data['id'];
        }
    } else {
        $client_id = newClient($order['phone'],
            $order['email'],
            $order['name'],
            $order['surname'],
            $order['thirdname'] ? $order['thirdname'] : '');
    }
    if ($client_id) {
        $stmt = dbaseConnect()->prepare('INSERT INTO orders (
        client_id, product_id, address, delivery_type, payment_type, comment)
        VALUES (:client_id, :product_id, :address, :delivery_type, :payment_type, :comment)');
        $result = $stmt->execute([
            'client_id' => $client_id,
            'product_id' => $order['product_id'],
            'address' => (!empty($order['address'])) ? implode(',', $order['address']) : '',
            'delivery_type' => $order['delivery_type'],
            'payment_type' => $order['payment_type'],
            'comment' => (!empty($order['comment'])) ? $order['comment'] : ''
        ]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    else {
        return false;
    }
}

function isOldClient($phone, $email)
{
    $stmt = dbaseConnect()->prepare('SELECT id FROM clients WHERE phone = :phone AND email = :email');
    $stmt->execute(['phone' => $phone, 'email' => $email]);
    return $stmt->fetchAll();
}

function newClient($phone, $email, $name, $surname, $thirdname = '')
{
    $stmt = dbaseConnect()->prepare('INSERT INTO clients (phone, email, name, surname, thirdname)
    VALUES (:phone, :email, :name, :surname, :thirdname)');
    $stmt->execute([
        'phone' => $phone,
        'email' => $email,
        'name' => $name,
        'surname' => $surname,
        'thirdname' => $thirdname
    ]);
    return dbaseConnect()->lastInsertId();
}

function addProduct(array $product)
{
    try {
        $stmt = dbaseConnect();
        $stmt->beginTransaction();
        $prepared = $stmt->prepare('INSERT INTO products (name, price, new, sale) VALUES (:name, :price, :new, :sale)');
        if (!$prepared->execute([
            'name' => $product['name'],
            'price' => $product['price'],
            'new' => $product['new'],
            'sale' => $product['sale']
        ])) {
           throw new Exception('Не удалось добавить продукт');
        }
        $product_id = $stmt->lastInsertId();
        $prepared = $stmt->prepare("UPDATE products SET img_path = :img_path WHERE id = :id");
        if (!$prepared->execute([
            'img_path' => "/img/products/product-$product_id.jpg",
            'id' => $product_id
        ])) {
            throw new Exception('Не удалось добавить путь для изображения');
        }
        $prepared = $stmt->prepare('SELECT img_path FROM products WHERE id = ?');
        $prepared->execute([$product_id]);
        $path = $prepared->fetch();
        if (!(move_uploaded_file($product['photo'], $_SERVER['DOCUMENT_ROOT'] . $path['img_path']))) {
            throw new Exception('Произошла ошибка при сохранении файла файла ');
        }
        $prepared = $stmt->prepare('INSERT INTO category_product (category_id, product_id) VALUES (:category_id , :product_id)');
        foreach ($product['categories'] as $category) {
            if (!$prepared->execute(['category_id' => $category, 'product_id' => $product_id])) {
                throw new Exception('Не удалось добавить категорию');
            }
        }
        $stmt->commit();
        return true;
    } catch (Exception $e) {
        $stmt->rollBack();
        return $e->getMessage();
    }
}

function editProduct($old, $new)
{
    foreach (getCategoryByProductId($old['id']) as $key => $value) {
        $old_categories[] = $value['id'];
    }
    if ($old['name'] == $new['name'] && $old['price'] == $new['price'] && $new['photo'] == null
    && $old_categories === $new['categories'] && $old['new'] == $new['new'] && $old['sale'] == $new['sale']) {
        return 'Вы не внесли никаких изменений';
    }
    if ($old['name'] != $new['name']) {
        $colspan[] = 'name = :name';
        $values['name'] = $new['name'];
    }
    if ($old['price'] != $new['price']) {
        $colspan[] = 'price = :price';
        $values['price'] = $new['price'];
    }
    if ($old['new'] != $new['new']) {
        $colspan[] = 'new = :new';
        $values['new'] = $new['new'];
    }
    if ($old['sale'] != $new['sale']) {
        $colspan[] = 'sale = :sale';
        $values['sale'] = $new['sale'];
    }
    if (!empty($colspan) && !empty($values)) {
        $values['id'] = $old['id'];
        $prepared_colspan = implode(', ', $colspan);
        $query = "UPDATE products SET $prepared_colspan WHERE id = :id;";
    }
    else {
        $query = null;
    }
    foreach ($new['categories'] as $category) {
        if (!in_array($category, $old_categories)) {
            $new_categories[] = $category;
        }
    }
    foreach ($old_categories as $category) {
        if (!in_array($category, $new['categories'])) {
            $categories_to_delete[] = $category;
        }
    }
    try {
        $stmt = dbaseConnect();
        $stmt->beginTransaction();
        if ($query !== null) {
            $prepared = $stmt->prepare($query);
            if (!$prepared->execute($values)) {
                throw new Exception('Данные о товаре не обновлены');
            }
        }
        if (!empty($new_categories)) {
            $prepared = $stmt->prepare('INSERT INTO category_product (category_id, product_id) VALUES (:category_id, :product_id)');
            foreach ($new_categories as $category) {
                if (!$prepared->execute(['category_id' => $category, 'product_id' => $old['id']])) {
                    throw new Exception('Категории товаров не были обновлены');
                }
            }
        }
        if (!empty($categories_to_delete)) {
            $prepared = $stmt->prepare('DELETE FROM category_product WHERE category_id = :category_id AND product_id = :product_id');
            foreach ($categories_to_delete as $category) {
                if (!$prepared->execute(['category_id' => $category, 'product_id' => $old['id']])) {
                    throw new Exception('Старые категории товаров не удалены');
                }
            }
        }
        if ($new['photo'] !== null) {
            if (!(move_uploaded_file($new['photo'], $_SERVER['DOCUMENT_ROOT'] . $old['img_path']))) {
                throw new Exception('Произошла ошибка при сохранении файла файла ');
            }
        }
        $stmt->commit();
        return true;
    } catch (Exception $e) {
        $stmt->rollBack();
        return $e->getMessage();
    }
}

function deleteProduct($product_id)
{
    $stmt = dbaseConnect()->prepare('DELETE FROM products WHERE id = ?');
    if ($stmt->execute([$product_id])) {
        return true;
    }
    else {
        return false;
    }
}

function getOrderById($id)
{
    $stmt = dbaseConnect()->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

function changeOrderStatus($id, $status)
{
    $stmt = dbaseConnect()->prepare('UPDATE orders SET status = :status WHERE id = :id');
    if ($stmt->execute(['status' => $status, 'id' =>$id])) {
        return true;
    } else {
        return false;
    }
}