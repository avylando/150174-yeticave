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

function set_timer() {
    date_default_timezone_set('Europe/Moscow');
    $diff = strtotime('tomorrow') - time();
    $hours = floor($diff / 3600);
    $minutes = floor(($diff / 60) - ($hours * 60));
    $time_left = $hours . ':' . $minutes;

    return $time_left;
}

?>
