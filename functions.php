<?php

function render_template ($template_src, $data) {
    if (file_exists($template_src)) {
        // foreach ($data as $var_name => $value) {
        //     $$var_name = $value;
        // }
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

?>
