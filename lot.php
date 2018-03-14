<?php

require_once 'init.php';

$lot_id = null;
$lot = null;
$bets = [];

if (isset($_GET['id'])) {
    try {
        $user_id = intval($_SESSION['user']['id']) ?? 0;
        $lot_id = intval($_GET['id']);
        $lot = get_lot_by_id($db_link, $lot_id, $user_id);

        if ($lot) {
            $bets = get_bets_by_lot_id($db_link, $lot_id);

        } else {
            http_response_code(404);
            $page_content = render_template('templates/error.php', ['error' => 'Лот не найден']);
        }

    } catch (Exception $error) {
        $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);
    }
}

$viewed_ids = [];

if (!empty($_COOKIE) && isset($_COOKIE['history'])) {
    $viewed_ids = json_decode($_COOKIE['history']);
}

if (!in_array($lot_id, $viewed_ids)) {
    array_push($viewed_ids, $lot_id);
    $updated_history = json_encode($viewed_ids);
    setcookie('history', $updated_history, strtotime('+5 days'));
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
