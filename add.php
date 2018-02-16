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

        if (is_uploaded_file($_FILES['photo']['tmp_name']) && empty($errors)) {
            $tmp_name = $_FILES['photo']['tmp_name'];
            $path = 'img/' . $_FILES['photo']['name'];

            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($file_info, $tmp_name);

            if ($file_type !== "image/png" && $file_type !== "image/jpeg" && $file_type !== "image/gif") {
                $errors['photo'] = 'Загрузите картинку в поддерживаемом формате (PNG, JPG, GIF)';

            } else {
                move_uploaded_file($tmp_name, $path);
                $lot['photo'] = $path;
            }
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
    'categories' => $categories,
    'content'    => $page_content,
]);

print($layout_content);
