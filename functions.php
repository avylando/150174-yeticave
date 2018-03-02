<?php

require_once 'mysql_helper.php';

function render_template ($template_src, $data) {
    if (file_exists($template_src)) {
        extract($data);
        ob_start();
        include $template_src;
        return ob_get_clean();
    }

    return '';
}

function format_price($price) {
    if (is_numeric($price)) {
        $int_price = ceil($price);
        if ($int_price >= 1000) {
            $int_price = number_format($int_price, 0, '.', ' ');
        }

        $formatted_price = $int_price . ' ';
        return $formatted_price;
    }

    return '';
}

function set_timer($date) {
    if (strtotime($date)) {
        date_default_timezone_set('Europe/Moscow');
        $diff = strtotime($date) - time();
        $hours = floor($diff / 3600);
        $time_left = '';

        if ($hours > 23) {
            $days = floor($hours / 24);
            $time_left = $days . ' дн.';

        } else {
            $minutes = floor(($diff / 60) - ($hours * 60));

            if (strlen($minutes) === 1) {
                $minutes = '0' . $minutes;
            }

            $time_left = $hours . ':' . $minutes;
        }

        return $time_left;
    }

    return 'Некорректный формат даты';
}

function check_date($date) {
    if (strtotime($date)) {
        $end_date = strtotime($date);
        $days_remain = floor(($end_date - time()) / 86400);

        if ($days_remain < 0) {
            return 'Введите корректную дату';
        }

        return '';
    }

    return 'Введите дату в формате «ДД.ММ.ГГГГ»';
}

function check_image_format($file) {
    $tmp_name = $file['tmp_name'];
    $path = 'img/' . $file['name'];

    $file_type = mime_content_type($tmp_name);

    if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
        return false;
    }

    move_uploaded_file($tmp_name, $path);
    return $path;
}

function check_authorization($session) {
    $result = [];

    if (isset($session['user'])) {
        $result['is_authorized'] = true;
        $result['user'] = $session['user'];
    }

    return $result;
}

function get_categories($connect) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = 'SELECT * FROM category';
    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_active_lots($connect) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = 'SELECT lot.id, creation_date, lot.name, category.name AS category, message, photo, start_price, step, expiration_date,
    (SELECT COUNT(*) FROM bet WHERE lot.id = bet.lot_id) AS bets_number
    FROM lot INNER JOIN category ON category.id = lot.category_id
    WHERE NOW() BETWEEN creation_date AND expiration_date
    ORDER BY creation_date DESC';

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_lot_by_id($connect, $id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }
    $id = intval($id);

    $sql = 'SELECT lot.id, lot.name, category.name AS category, message, photo, start_price, step, expiration_date FROM lot
    JOIN category ON lot.category_id = category.id
    WHERE lot.id = ' . $id;

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    if (!mysqli_num_rows($result)) {
        http_response_code(404);
        return false;
    }

    return $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
}

function get_bets_by_lot_id($connect, $id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT bet.sum, DATE_FORMAT (bet.date, '%d.%m.%y %H:%i') AS date, user.name AS user FROM bet
    JOIN lot ON bet.lot_id = lot.id
    JOIN user ON bet.user_id = user.id
    WHERE bet.lot_id = '$id'
    ORDER BY bet.date DESC, bet.sum DESC";

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

}

function get_max_bet_for_lot($connect, $id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT MAX(sum) AS max_bet FROM bet WHERE lot_id = " . $id;

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    $row = mysqli_fetch_assoc($result);
    $max = $row['max_bet'];

    return $max;
}

function get_user_by_login($connect, $login) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT * FROM user
    WHERE email = ?";

    $input_user = mysqli_real_escape_string($connect, $login);
    $stmt = db_get_prepare_stmt($connect, $sql, [$input_user]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $current_user = mysqli_fetch_assoc($result);
}

function search_email_in_db($connect, $email) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT email FROM user WHERE email = ?";

    $search_email = mysqli_real_escape_string($connect, $email);
    $stmt = db_get_prepare_stmt($connect, $sql, [$search_email]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    if (!empty(mysqli_fetch_assoc($result))) {
        return 'Пользователь с таким адресом уже зарегистрирован';
    }

    return null;
}

function add_user($connect, $userdata, $file_path) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "INSERT INTO user (name, email, password, contacts, avatar)
    VALUES (?, ?, ?, ?, ?)";

    $user_password = password_hash($userdata['password'], PASSWORD_DEFAULT);
    $stmt = db_get_prepare_stmt($connect, $sql, [$userdata['user_name'], $userdata['email'], $user_password, $userdata['contacts'], $file_path]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

function add_lot($connect, $lot, $user_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "INSERT INTO lot (name, category_id, message, photo, start_price, step, expiration_date, author_user_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($connect, $sql, [$lot['name'], $lot['category'], $lot['message'], $lot['photo'], $lot['start_price'], $lot['step'], $lot['expiration_date'], $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

function add_bet($connect, $bet_sum, $lot_id, $user_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "INSERT INTO bet (sum, lot_id, user_id) VALUES (?, ?, ?)";

    $stmt = db_get_prepare_stmt($connect, $sql, [$bet_sum, $lot_id, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

function update_price($connect, $price, $lot_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "UPDATE lot SET start_price = ? WHERE id = " . $lot_id;

    $stmt = db_get_prepare_stmt($connect, $sql, [$price]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

function count_lots_by_keyword($connect, $keyword) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT DISTINCT COUNT(*) AS counter FROM lot WHERE MATCH(lot.name, lot.message) AGAINST(?)";

    $stmt = db_get_prepare_stmt($connect, $sql, [$keyword]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $count = mysqli_fetch_assoc($result)['counter'];
}

function search_lots_by_keyword($connect, $keyword, $limit, $offset) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT lot.id, lot.name, category.name AS category, lot.message, lot.photo, lot.start_price, lot.step,
        lot.expiration_date, (SELECT COUNT(*) FROM bet WHERE lot.id = bet.lot_id) AS bets_number
        FROM lot INNER JOIN category ON lot.category_id = category.id WHERE MATCH(lot.name, lot.message) AGAINST(?)
        ORDER BY creation_date DESC LIMIT $limit OFFSET ". $offset;

    $stmt = db_get_prepare_stmt($connect, $sql, [$keyword]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function prepare_data_for_layout($connect, $title, $session, $content) {
    try {
        $categories = get_categories($connect);

    } catch (Exception $error) {
        $title = 'Ошибка';
        $categories = [];
        $content = render_template('templates/error.php', ['error' => $error->getMessage()]);
    }

    return $data = [
        'title' => $title,
        'session' => check_authorization($session),
        'categories' => $categories,
        'content' => $content
    ];
}
