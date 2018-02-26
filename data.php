<?php

require_once 'functions.php';
require_once 'init.php';

session_start();

$is_authorized = false;
$user = [];

if (!empty($_SESSION)) {

    if (isset($_SESSION['user'])) {
    $is_authorized = true;
    $user = $_SESSION['user'];
    }
}

$categories = [];
$lots = [];

if (!$db_link) {
    show_error(mysqli_connect_error());

} else {
    // Запрос списка открытых лотов
    $sql = 'SELECT lot.id, creation_date, lot.name, category.name AS category, message, photo, start_price, step, expiration_date
    FROM lot JOIN category ON category.id = lot.category_id
    WHERE NOW() BETWEEN creation_date AND expiration_date
    ORDER BY creation_date DESC';
    $result = mysqli_query($db_link, $sql);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    } else {
        show_error(mysqli_error($db_link));
    }

    // Запрос списка категорий
    $sql = 'SELECT name FROM category';
    $result = mysqli_query($db_link, $sql);

    if ($result) {
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($rows as $row) {
            array_push($categories, $row['name']);
        }

    } else {
        show_error(mysqli_error($db_link));
    }
}
