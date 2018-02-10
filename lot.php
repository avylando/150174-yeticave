<?php

require_once 'functions.php';
require_once 'data.php';

$lot = null;

if (isset($_GET['lot_id'])) {
    $lot_id = $_GET['lot_id'];

    foreach ($lots as $id => $item) {
        if ($id == $lot_id) {
            $lot = $item;
            break;
        }
    }
}

if (!$lot) {
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
