<?php

require_once 'init.php';
require_once 'assets/getwinner.php';

try {
    $page_content = render_template('templates/index.php', [
        'categories' => get_categories($db_link),
        'lots' => get_active_lots($db_link)
    ]);

} catch (Exception $error)  {
    $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Главная', $_SESSION, $page_content));

print($layout_content);
