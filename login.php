<?php

require_once 'init.php';

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

    if (empty($errors)) {
        if (!$db_link) {
            show_error(mysqli_connect_error());

        } else {
            $input_user = mysqli_real_escape_string($db_link, $login['email']);
            $sql = "SELECT email, password, name, contacts, avatar FROM user
            WHERE email = '$input_user'";
            $result = mysqli_query($db_link, $sql);

            if ($result) {
                $current_user = mysqli_fetch_assoc($result);

                if (!empty($current_user)) {
                    if (password_verify($login['password'], $current_user['password'])) {
                        $_SESSION['user'] = $current_user;
                        header('Location: /index.php');
                        exit();

                    } else {
                        $errors['password'] = 'Вы ввели неверный пароль';
                    }

                } else {
                    $errors['email'] = 'Пользователь не найден';
                }

            } else {
                show_error(mysqli_error($db_link));
            }
        }

    }

    if (!empty($errors)) {
        $page_content = render_template('templates/login.php', [
            'categories' => get_categories($db_link),
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
            'categories' => get_categories($db_link),
            'login' => $login,
            'errors' => $errors
        ]);
    }
}

$layout_content = render_template('templates/layout.php', prepare_data_for_layout($db_link, 'Вход', $page_content));

print($layout_content);
