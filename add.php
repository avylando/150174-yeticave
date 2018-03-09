<?php

require_once 'init.php';

if (empty($_SESSION['user'])) {
    http_response_code(403);
    $page_content = render_template('templates/error.php', [
        'error' => 'Только для зарегистрированных пользователей. Пожалуйста, авторизуйтесь, чтобы продолжить']);
    $layout_content = render_template('templates/layout.php',
    prepare_data_for_layout($db_link, 'Недостаточно прав', $_SESSION, $page_content));
    print($layout_content);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-lot'])) {
    $lot = $_POST;
    $errors = [];

    $required = ['name', 'category', 'message', 'start_price', 'step', 'expiration_date'];
    $dict = [
        'name' => 'наименование лота',
        'category' => 'категорию лота',
        'message' => 'описание лота',
        'start_price' => 'начальную цену',
        'step' => 'шаг ставки',
        'expiration_date' => 'дату завершения торгов'
    ];

    foreach ($required as $field) {
        if (empty($lot[$field])) {
            $errors[$field] = 'Введите ' . $dict[$field];
        }

        if ($lot['category'] === 'Выберите категорию') {
            $errors['category'] = 'Выберите ' . $dict['category'];
        }
    }

    if (!empty($lot['start_price']) && intval($lot['start_price']) <= 0) {
        $errors['start_price'] = 'Значение должно быть больше нуля';
    }

    if (!empty($lot['step']) && intval($lot['step']) <= 0) {
        $errors['step'] = 'Значение должно быть больше нуля';
    }

    if (!empty($lot['expiration_date'])) {
        $check_result = check_date($lot['expiration_date']);

        if (!empty($check_result)) {
            $errors['expiration_date'] = $check_result;
        }
    }

    if (is_uploaded_file($_FILES['photo']['tmp_name']) && empty($errors)) {
        $image_path = check_image_format($_FILES['photo']);

        if ($image_path) {
            $lot['photo'] = $image_path;

        } else {
            $errors['photo'] = 'Загрузите изображение в поддерживаемом формате (PNG, JPG)';
        }

    } else if (!is_uploaded_file($_FILES['photo']['tmp_name'])) {
        $errors['photo'] = 'Загрузите изображение';
    }

    if (count($errors)) {
        try {
            $page_content = render_template('templates/add-lot.php', [
                'categories' => get_categories($db_link),
                'lot' => $lot,
                'errors' => $errors
            ]);

        } catch (Exception $error)  {
            $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
        }

    } else {
        try {
            $result = add_lot($db_link, $lot, $_SESSION['user']['id']);
            $lot_id = mysqli_insert_id($db_link);
            header('Location: /lot.php?id=' . $lot_id);
            exit();

        } catch (Exception $error)  {
            $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
        }
    }

} else {
    try {
        $page_content = render_template('templates/add-lot.php', [
            'categories' => get_categories($db_link),
            'lot' => $lot,
            'errors' => $errors
        ]);

    } catch (Exception $error)  {
        $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
    }
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Добавить лот', $_SESSION, $page_content));

print($layout_content);
