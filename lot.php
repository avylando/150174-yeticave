<?php

require_once 'init.php';

$lot = null;
$current_id = null;

if (isset($_GET['id'])) {

    // Запрос лота
    $id = intval($_GET['id']);
    $current_id = $id;
    $sql = 'SELECT lot.name, category.name AS category, message, photo, start_price, step, expiration_date FROM lot
    JOIN category ON lot.category_id = category.id
    WHERE lot.id = ' . $id;

    $result = mysqli_query($db_link, $sql);

    if ($result) {
        if (!mysqli_num_rows($result)) {
            http_response_code(404);
            show_error('Лот не найден');

        } else {
            $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

            // Запрос ставок пользователей
            $sql = 'SELECT bet.sum, user.name AS user, bet.date FROM bet
            JOIN lot ON bet.lot_id = lot.id
            JOIN user ON bet.user_id = user.id
            WHERE bet.lot_id = ' . $id;

            $result = mysqli_query($db_link, $sql);
            $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

    } else {
        show_error(mysqli_error($db_link));
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

if (empty($lot)) {
    http_response_code(404);
    exit();
}

$page_content = render_template('templates/lot.php', [
    'lot' => $lot,
    'session' => check_authorization(),
    'bets' => $bets
]);

$layout_content = render_template('templates/layout.php', prepare_data_for_layout($db_link, 'Просмотр лота', $page_content));

print($layout_content);
