<?php

require_once 'init.php';
require_once 'mysql_helper.php';

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

        $search_email = mysqli_real_escape_string($db_link, $sign_up['email']);
        $sql = "SELECT email FROM user WHERE email = '$search_email'";

        $result = mysqli_query($db_link, $sql);
        if ($result) {
            $found = mysqli_fetch_assoc($result);

            if(!empty($found)) {
                $errors['email'] = 'Пользователь с таким адресом уже зарегистрирован';
            }
        } else {
            show_error(mysqli_error($db_link));
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

        $sql = "INSERT INTO user (name, email, password, contacts, avatar)
        VALUES (?, ?, ?, ?, ?)";

        $user_password = password_hash($sign_up['password'], PASSWORD_DEFAULT);
        $stmt = db_get_prepare_stmt($db_link, $sql, [$sign_up['user_name'], $sign_up['email'], $user_password, $sign_up['contacts'], $avatar_path]);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header('Location: login.php');
            exit();

        } else {
            show_error(mysqli_error($db_link));
        }

    } else {
        $page_content = render_template('templates/sign-up.php', [
            'categories' => get_categories($db_link),
            'sign_up' => $sign_up,
            'errors' => $errors
        ]);
    }

} else {
    $page_content = render_template('templates/sign-up.php', [
        'categories' => get_categories($db_link),
        'sign_up' => $sign_up,
        'errors' => $errors
    ]);
}

$layout_content = render_template('templates/layout.php', prepare_data_for_layout($db_link, 'Регистрация', $page_content));

print($layout_content);
