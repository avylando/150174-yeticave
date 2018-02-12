<?php

require_once 'functions.php';
require_once 'data.php';

$lot = null;

if (isset($_GET['id'])) {

    foreach ($lots as $id => $item) {
        if ($id == $_GET['id']) {
            $lot = $item;
            break;
        }
    }
}

if (empty($lot)) {
    http_response_code(404);
}

$page_content = render_template('templates/lot.php', [
    'lot' => $lot
]);

$layout_content = render_template('templates/layout.php', [
    'title' => 'Просмотр лота',
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
