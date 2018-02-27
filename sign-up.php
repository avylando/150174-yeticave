<?php

require_once 'init.php';

$sign_up = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sign-up'])) {
    $sign_up = $_POST;
    $required = ['email', 'password', 'user_name', 'contacts'];
    $dict = [
        'email' => 'E-mail',
        'password' => 'пароль',
        'user_name' => 'имя пользователя',
        'contacts' => 'контактные данные'
    ];

    foreach ($required as $field) {

        if (empty($sign_up[$field])) {
            $errors[$field] = 'Введите ' . $dict[$field];
        }
    }

    if (isset($sign_up['email']) && !filter_var($sign_up['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите валидный E-mail';

    } else {
        try {
            $result = search_email_in_db($db_link, $sign_up['email']);

            if ($result) {
                $errors['email'] = $result;
            }

        } catch (Exception $error) {
            $page_content['error'] = $error->getMessage();
        }
    }

    $avatar_path = 'img/user.jpg';
    if (is_uploaded_file($_FILES['avatar']['tmp_name']) && empty($errors)) {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $avatar_path = 'img/' . $_FILES['avatar']['name'];

        $file_type = mime_content_type($tmp_name);

        if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
            $errors['avatar'] = 'Загрузите изображение в поддерживаемом формате (PNG, JPG)';

        } else {
            move_uploaded_file($tmp_name, $avatar_path);
        }
    }

    if (empty($errors)) {

        try {
            $result = add_user($db_link, $sign_up, $avatar_path);

            if ($result) {
                header('Location: login.php');
                exit();
            }

        } catch (Exception $error) {
            $page_content['error'] = $error->getMessage();
        }

    } else {
        try {
            $page_content = render_template('templates/sign-up.php', [
                'categories' => get_categories($db_link),
                'sign_up' => $sign_up,
                'errors' => $errors
            ]);

        } catch (Exception $error)  {
            $page_content['error'] = $error->getMessage();
        }
    }

} else {
    try {
        $page_content = render_template('templates/sign-up.php', [
            'categories' => get_categories($db_link),
            'sign_up' => $sign_up,
            'errors' => $errors
        ]);

    } catch (Exception $error)  {
        $page_content['error'] = $error->getMessage();
    }
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Регистрация', $_SESSION, $page_content));

print($layout_content);
