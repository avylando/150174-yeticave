<?php

require_once 'functions.php';
require_once 'data.php';

$lot = null;

if (isset($_GET['id'])) {
    foreach ($lots as $id => $item) {
        if ($id === (int) $_GET['id']) {
            $lot = $item;
            $current_id = $id;

            break;
        }
    }
}

$viewed_ids = [];

if (!empty($_COOKIE) && isset($_COOKIE['history'])) {
    $viewed_ids = json_decode($_COOKIE['history']);
}

if (!in_array($current_id, $viewed_ids)) {
    array_push($viewed_ids, $current_id);
    $updated_history = json_encode($viewed_ids);
    setcookie('history', $updated_history, strtotime('+15 days'));
}

if (empty($lot)) {
    http_response_code(404);
    exit();
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
