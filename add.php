<?php

require_once 'functions.php';
require_once 'data.php';

session_start();

if (!empty($_SESSION) && isset($_SESSION['user'])) {
    $lot = null;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-lot'])) {
        $lot = $_POST;

        $required = ['title', 'category', 'message', 'price', 'step', 'date'];
        $dict = [
            'title' => 'наименование лота',
            'category' => 'категорию лота',
            'message' => 'описание лота',
            'price' => 'начальную цену',
            'step' => 'шаг ставки',
            'date' => 'дату завершения торгов'
        ];

        foreach ($required as $field) {
            if (empty($lot[$field])) {
                $errors[$field] = 'Введите ' . $dict[$field];
            }

            if ($lot['category'] === 'Выберите категорию') {
                $errors['category'] = 'Выберите ' . $dict['category'];
            }
        }

        if (isset($lot['price']) && (int) $lot['price'] <= 0) {
            $errors['price'] = 'Значение должно быть больше нуля';
        }

        if (isset($lot['step']) && (int) $lot['step'] <= 0) {
            $errors['step'] = 'Значение должно быть больше нуля';
        }

        if (isset($lot['date'])) {
            if (strtotime($lot['date'])) {
                $end_date = strtotime($lot['date']);
                $days_remain = floor(($end_date - time()) / 86400);

                if ($days_remain < 0) {
                    $errors['date'] = 'Введите корректную дату';

                }

            } else {
                $errors['date'] = 'Введите дату в формате «ДД.ММ.ГГГГ»';
            }
        }

        if (is_uploaded_file($_FILES['photo']['tmp_name']) && empty($errors)) {
            $tmp_name = $_FILES['photo']['tmp_name'];
            $path = 'img/' . $_FILES['photo']['name'];

            $file_type = mime_content_type($tmp_name);

            if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
                $errors['photo'] = 'Загрузите изображение в поддерживаемом формате (PNG, JPG)';

            } else {
                move_uploaded_file($tmp_name, $path);
                $lot['photo'] = $path;
            }
        } else if (!is_uploaded_file($_FILES['photo']['tmp_name'])) {
            $errors['photo'] = 'Загрузите изображение';
        }

        if (count($errors)) {
            $page_content = render_template('templates/add-lot.php', [
                'categories' => $categories,
                'lot' => $lot,
                'errors' => $errors
            ]);

        } else {
            $page_content = render_template('templates/lot.php', [
                'lot' => $lot
            ]);
        }

    } else {
        $page_content = render_template('templates/add-lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'errors' => $errors
        ]);
    }

} else {
    http_response_code(403);
    exit();
}

$layout_content = render_template('templates/layout.php', [
    'title'      => 'Добавить лот',
    'session' => [
        'is_authorized' => $is_authorized,
        'user' => $user
    ],
    'categories' => $categories,
    'content'    => $page_content,
]);

print($layout_content);
