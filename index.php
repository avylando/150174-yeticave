<?php

require_once 'init.php';

$session = check_authorization();

$page_content = render_template('templates/index.php', [
    'categories' => get_categories($db_link),
    'lots' => get_active_lots($db_link)
]);

$layout_content = render_template('templates/layout.php', prepare_data_for_layout($db_link, 'Главная страница', $page_content));

print($layout_content);
