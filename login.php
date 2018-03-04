<?php

require_once 'init.php';

$login = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $login = $_POST;
    $required = ['email', 'password'];
    $dict = ['email' => 'E-mail', 'password' => 'пароль'];

    foreach ($required as $field) {

        if (empty($login[$field])) {
            $errors[$field] = 'Введите ' . $dict[$field];
        }
    }

    if (empty($errors)) {
        try {
            $current_user = get_user_by_login($db_link, $login['email']);

            if (!empty($current_user)) {
                if (password_verify($login['password'], $current_user['password'])) {
                    $_SESSION['user'] = $current_user;
                    header('Location: /index.php');
                    exit();
                }

                $errors['password'] = 'Вы ввели неверный пароль';

            } else {
                $errors['email'] = 'Пользователь не найден';
            }

        } catch (Exception $error)  {
            $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
        }
    }

    if (!empty($errors)) {
        $page_content = render_template('templates/login.php', [
            'login' => $login,
            'errors' => $errors
        ]);
    }

} else {
    if (!empty($_SESSION) && isset($_SESSION['user'])) {
        header('Location: /index.php');
        exit();
    }

    $page_content = render_template('templates/login.php', [
        'login' => $login,
        'errors' => $errors
    ]);
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Вход', $_SESSION, $page_content));

print($layout_content);
