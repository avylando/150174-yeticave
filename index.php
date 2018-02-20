<?php
require_once 'functions.php';
require_once 'data.php';

$page_content = render_template('templates/index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = render_template('templates/layout.php', [
    'title' => 'Главная страница',
    'session' => [
        'is_authorized' => $is_authorized,
        'user' => $user
    ],
    'categories' => $categories,
    'content' => $page_content
]);

print($layout_content);
