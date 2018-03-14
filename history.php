<?php

require_once 'init.php';

$lots = [];
$related_lots = [];
$current_page = null;
$pages_number = 1;
$pages = 1;

if (!empty($_COOKIE) && isset($_COOKIE['history'])) {
    $view_history = $_COOKIE['history'];
    $viewed_ids = json_decode($view_history);
    $lots = get_lots_by_ids($db_link, $viewed_ids);

    $current_page = $_GET['page'] ?? 1;

    $limit = 9;
    $offset = ($current_page - 1) * $limit;

    $current_items = count($lots);
    $pages_number = ceil($current_items/$limit);
    $pages = range(1, $pages_number);

    $related_lots = array_slice($lots, $offset, $limit);

    // foreach ($viewed_ids as $id) {
    //     foreach ($lots as $lot) {
    //         if ($id == $lot['id']) {
    //             array_push($related_lots, $lot);
    //         }
    //     }
    // }
}

$page_content = render_template('templates/history.php', [
    'related_lots' => $related_lots,
    'current_page' => $current_page,
    'pages_number' => $pages_number,
    'pages' => $pages
    ]
);

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'История просмотров', $_SESSION, $page_content));

print($layout_content);
