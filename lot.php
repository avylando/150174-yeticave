<?php

require_once 'init.php';

$current_id = null;
$lot = null;
$bets = [];

if (isset($_GET['id'])) {
    try {
        $id = intval($_GET['id']);
        $current_id = $id;
        $lot = get_lot_by_id($db_link, $id);

        if ($lot) {
            $bets = get_bets_by_lot_id($db_link, $id);

        } else {
            http_response_code(404);
            exit();
        }

    } catch (Exception $error) {
        $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
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

if (!isset($page_content)) {
    $page_content = render_template('templates/lot.php', [
        'lot' => $lot,
        'session' => check_authorization($_SESSION),
        'bets' => $bets
    ]);
}

$layout_content = render_template('templates/layout.php',
prepare_data_for_layout($db_link, 'Просмотр лота', $_SESSION, $page_content));

print($layout_content);
