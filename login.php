<?php

require_once 'functions.php';
require_once 'data.php';
require_once 'userdata.php';

session_start();

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

    $current_user = search_user_by_email($login['email'], $users);

    if (empty($errors) && isset($current_user)) {

        if (password_verify($login['password'], $current_user['password'])) {
            $_SESSION['user'] = $current_user;
            header('Location: /index.php');
            exit();

        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }

    } else if (empty($errors) && empty($current_user)) {
        $errors['email'] = 'Пользователь не найден';
    }

    if (count($errors)) {
        $page_content = render_template('templates/login.php', [
            'categories' => $categories,
            'login' => $login,
            'errors' => $errors
        ]);
    }

} else {

    if (!empty($_SESSION) && isset($_SESSION['user'])) {
        header('Location: /index.php');
        exit();

    } else {
        $page_content = render_template('templates/login.php', [
            'categories' => $categories,
            'login' => $login,
            'errors' => $errors
        ]);
    }
}

$layout_content = render_template('templates/layout.php', [
    'title' => 'Вход',
    'categories' => $categories,
    'content' => $page_content
]);

print($layout_content);
