<?php

require_once 'functions.php';
require_once 'data.php';

$related_lots = [];

if (!empty($_COOKIE) && isset($_COOKIE['history'])) {
    $view_history = $_COOKIE['history'];
    $viewed_ids = json_decode($view_history);

    foreach ($viewed_ids as $id) {
        if (isset($lots[$id])) {
            $related_lots[$id] = $lots[$id];
        }
    }
}

$page_content = render_template('templates/history.php', [
    'categories' => $categories,
    'related_lots' => $related_lots
]);

$layout_content = render_template('templates/layout.php', [
    'title' => 'История просмотров',
    'session' => [
        'is_authorized' => $is_authorized,
        'user' => $user
    ],
    'categories' => $categories,
    'content' => $page_content
]);

print($layout_content);
