<?php

require_once 'init.php';

if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cost'])) {
        $bet = intval($_POST['cost']);
        $lot_id = intval($_GET['lot_id']) ?? 0;

        if (empty($bet)) {
            header("Location: /lot.php?id=" . $lot_id);
            exit();

        } else {
            try {
                $lot = get_lot_by_id($db_link, $lot_id);

                if ($lot) {
                    $max_bet = intval(get_max_bet_for_lot($db_link, $lot_id));
                    $user_id = $_SESSION['user']['id'] ?? '';

                    if ($max_bet == 0) {
                        $max_bet = $lot['start_price'];
                    }

                    if ($bet >= ($max_bet + $lot['step'])) {
                        mysqli_query($db_link, "START TRANSACTION");

                        $result1 = add_bet($db_link, $bet, $lot_id, $user_id);
                        $result2 = update_price($db_link, $bet, $lot_id);

                        if ($result1 && $result2) {
                            mysqli_query($db_link, "COMMIT");
                        }
                        else {
                            mysqli_query($db_link, "ROLLBACK");
                        }

                        header("Location: /lot.php?id=" . $lot_id);
                        exit();

                    } else {
                        header("Location: /lot.php?id=" . $lot_id);
                        exit();
                    }

                } else {
                    header("Location: /lot.php?id=" . $lot_id);
                    exit();
                }

            } catch (Exception $error)  {
                $page_content = render_template('templates/error.php', ['error' => $error->getMessage()]);

                $layout_content = render_template('templates/layout.php',
                prepare_data_for_layout($db_link, 'Ошибка', $_SESSION, $page_content));

                print($layout_content);
            }
        }
    }

} else {
    http_response_code(403);
    exit();
}


