<?php

require_once 'init.php';

if (!empty($_SESSION) && isset($_SESSION['user'])) {
    $lot = null;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-lot'])) {
        $lot = $_POST;

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

        if (isset($lot['start_price']) && (int) $lot['start_price'] <= 0) {
            $errors['start_price'] = 'Значение должно быть больше нуля';
        }

        if (isset($lot['step']) && (int) $lot['step'] <= 0) {
            $errors['step'] = 'Значение должно быть больше нуля';
        }

        if (isset($lot['expiration_date'])) {
            $check_result = check_date($lot['expiration_date']);

            if (!empty($check_result)) {
                $errors['expiration_date'] = $check_result;
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

} else {
    http_response_code(403);
    exit();
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Добавить лот', $_SESSION, $page_content));

print($layout_content);
