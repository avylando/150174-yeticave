<?php

require_once 'init.php';

$lots = [];
$related_lots = [];

if (!empty($_COOKIE) && isset($_COOKIE['history'])) {
    $view_history = $_COOKIE['history'];
    $viewed_ids = json_decode($view_history);
    $lots = get_active_lots($db_link);

    foreach ($viewed_ids as $id) {
        foreach ($lots as $lot) {
            if ($id == $lot['id']) {
                array_push($related_lots, $lot);
            }
        }
    }
}

try {
    $page_content = render_template('templates/history.php', [
        'categories' => get_categories($db_link),
        'related_lots' => $related_lots
    ]);

} catch (Exception $error)  {
    $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'История просмотров', $_SESSION, $page_content));

print($layout_content);
