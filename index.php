<?php
require_once 'functions.php';

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];


$lots = [
    0 => [
        'title' => '2014 Rossignol District Snowboard',
        'category' => $categories[0],
        'price' => 10999,
        'photo' => 'img/lot-1.jpg',
        'alt' => 'Сноуборд'
    ],

    1 => [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories[0],
        'price' => 	159999,
        'photo' => 'img/lot-2.jpg',
        'alt' => 'Сноуборд'
    ],

    2 => [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories[1],
        'price' => 	8000,
        'photo' => 'img/lot-3.jpg',
        'alt' => 'Крепления'
    ],

    3 => [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories[2],
        'price' => 	10999,
        'photo' => 'img/lot-4.jpg',
        'alt' => 'Ботинки для сноуборда'
    ],

    4 => [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories[3],
        'price' => 	7500,
        'photo' => 'img/lot-5.jpg',
        'alt' => 'Куртка для сноуборда'
    ],

    5 => [
        'title' => 'Маска Oakley Canopy',
        'category' => $categories[5],
        'price' => 	5400,
        'photo' => 'img/lot-6.jpg',
        'alt' => 'Маска для сноуборда'
    ]
];

$page_content = include_template('templates/index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('templates/layout.php', [
    'title' => 'Главная страница',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'categories' => $categories,
    'content' => $page_content
]);

print($layout_content);

?>
