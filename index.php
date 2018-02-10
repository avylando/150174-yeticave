<?php
require_once 'functions.php';
require_once 'data.php';

$page_content = render_template('templates/index.php', [
    'categories' => $categories,
    'lots' => $lots,
    'time_left' => $time_left
]);

$layout_content = render_template('templates/layout.php', [
    'title' => 'Главная страница',
    'authorization' => [
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar
        ],
    'categories' => $categories,
    'content' => $page_content
]);

print($layout_content);

?>
