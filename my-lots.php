<?php

require_once 'init.php';

$user_id= null;
$bets = [];

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    if ($user_id) {
        try {
            $bets = search_bets_by_user($db_link, $user_id);

        } catch (Exception $error) {
            $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
        }
    }
}

if (!isset($page_content)) {
    $page_content = render_template('templates/my-lots.php', ['user_id' => $user_id, 'bets' => $bets]);
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Мои ставки', $_SESSION, $page_content));

print($layout_content);
