<?php

require_once 'functions.php';
require_once 'data.php';

if (isset($_COOKIE['history'])) {
    $view_history = $_COOKIE['history'];

    $viewed_ids = json_decode($view_history);
    $related_lots = [];

    foreach ($viewed_ids as $id) {
        if (isset($lots[$id])) {
            $related_lots[$id] = $lots[$id];
        }
    }

    $page_content = render_template('templates/history.php', [
        'categories' => $categories,
        'related_lots' => $related_lots
    ]);
} else {
    $page_content = render_template('templates/history.php', [
        'categories' => $categories
    ]);
}

$layout_content = render_template('templates/layout.php', [
    'title' => 'История просмотров',
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
