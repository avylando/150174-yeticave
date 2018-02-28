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

    $sql = 'SELECT lot.id, creation_date, lot.name, category.name AS category, message, photo, start_price, step, expiration_date
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

    $sql = 'SELECT lot.name, category.name AS category, message, photo, start_price, step, expiration_date FROM lot
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
    WHERE bet.lot_id = " . $id;

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

}

function get_user_by_login($connect, $login) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT email, password, name, contacts, avatar FROM user
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
