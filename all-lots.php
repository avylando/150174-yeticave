<?php

require_once 'init.php';

$category_id= null;
$lots = [];
$current_page = null;
$pages_number = 1;
$pages = 1;

if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);

    if ($category_id) {
        try {
            $current_page = $_GET['page'] ?? 1;

            $limit = 9;
            $offset = ($current_page - 1) * $limit;

            $current_items = count_lots_by_category($db_link, $category_id);
            $pages_number = ceil($current_items/$limit);
            $pages = range(1, $pages_number);

            $lots = search_lots_by_category($db_link, $category_id, $limit, $offset);

        } catch (Exception $error) {
            $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
        }
    }
}

if (!isset($page_content)) {
    $page_content = render_template('templates/all-lots.php', [
        'category_id' => $category_id,
        'lots' => $lots,
        'pages_number' => $pages_number,
        'current_page' => $current_page,
        'pages' => $pages
    ]);
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Все лоты', $_SESSION, $page_content, $category_id));

print($layout_content);
