<?php

function render_template ($template_src, $data) {
    if (file_exists($template_src)) {
        extract($data);
        ob_start();
        require_once $template_src;
        return ob_get_clean();
    }

    return '';
}

function show_error ($error) {
    $page_content = render_template('templates/error.php', [
        'error' => $error
    ]);

    print render_template('templates/layout.php', [
        'title' => 'Ошибка',
        'content' => $page_content
    ]);
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

function check_authorization() {
    session_start();
    $session = [];

    if (!empty($_SESSION)) {

        if (isset($_SESSION['user'])) {
            $session['is_authorized'] = true;
            $session['user'] = $_SESSION['user'];
        }
    }

    return $session;
}

function get_categories($connect) {
    if (!$connect) {
        show_error(mysqli_connect_error());

    } else {
        $sql = 'SELECT name FROM category';
        $result = mysqli_query($connect, $sql);

        if ($result) {
            $categories = [];
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

            foreach ($rows as $row) {
                array_push($categories, $row['name']);
            }
            return $categories;

        } else {
            show_error(mysqli_error($connect));
        }
    }
}

function get_active_lots($connect) {
    if (!$connect) {
        show_error(mysqli_connect_error());

    } else {
        $sql = 'SELECT lot.id, creation_date, lot.name, category.name AS category, message, photo, start_price, step, expiration_date
        FROM lot JOIN category ON category.id = lot.category_id
        WHERE NOW() BETWEEN creation_date AND expiration_date';
        $result = mysqli_query($connect, $sql);

        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);

        } else {
            show_error(mysqli_error($connect));
        }
    }
}

function prepare_data_for_layout($connect, $title, $content) {
    return $data = [
        'title' => $title,
        'session' => check_authorization(),
        'categories' => get_categories($connect),
        'content' => $content
    ];

}

?>
