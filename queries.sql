-- Добавление категорий
INSERT INTO category (cat_name)
VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

-- Добавление пользователей
INSERT INTO user
SET user_name = 'Игнат',
email = 'ignat.v@gmail.com',
password = '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka',
contacts = 'phone: 89653426190';

INSERT INTO user
SET user_name = 'Леночка',
email = 'kitty_93@li.ru',
password = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa',
contacts = 'Adress: Gagarin st. 18';

INSERT INTO user
SET user_name = 'Руслан',
email = 'warrior07@mail.ru',
password = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW',
contacts = 'mobile: 89222310241. home: 7291082';

-- Добавление лотов
INSERT INTO lot
SET lot_name = '2014 Rossignol District Snowboard',
category_id = 1,
message = 'Борд',
photo = 'img/lot-1.jpg',
start_price = 10999,
step = 100,
expiration_date = '2018-03-10 00:00:00',
author_user_id = 1;

INSERT INTO lot
SET lot_name = 'DC Ply Mens 2016/2017 Snowboard',
category_id = 1,
message = 'Snowboard',
photo = 'img/lot-2.jpg',
start_price = 159999,
step = 1000,
expiration_date = '2018-04-01 00:00:00',
author_user_id = 3;

INSERT INTO lot
SET lot_name = 'Крепления Union Contact Pro 2015 года размер L/XL',
category_id = 2,
message = 'Почти новые крепления',
photo = 'img/lot-3.jpg',
start_price = 8000,
step = 500,
expiration_date = '2018-03-22 00:00:00',
author_user_id = 1;

INSERT INTO lot
SET lot_name = 'Ботинки для сноуборда DC Mutiny Charocal',
category_id = 3,
message = 'Клевые ботинки',
photo = 'img/lot-4.jpg',
start_price = 10999,
step = 200,
expiration_date = '2018-05-18 00:00:00',
author_user_id = 2;

INSERT INTO lot
SET lot_name = 'Куртка для сноуборда DC Mutiny Charocal',
category_id = 4,
message = 'Модная куртка',
photo = 'img/lot-5.jpg',
start_price = 7500,
step = 250,
expiration_date = '2018-04-21 00:00:00',
author_user_id = 3;

INSERT INTO lot
SET lot_name = 'Маска Oakley Canopy',
category_id = 5,
message = 'Подходит для любого времени суток',
photo = 'img/lot-6.jpg',
start_price = 5400,
step = 400,
expiration_date = '2018-03-13 00:00:00',
author_user_id = 2;


-- Добавление ставок
INSERT INTO bet
SET sum = 11499,
lot_id = 1,
user_id = 2;

INSERT INTO bet
SET sum = 12099,
lot_id = 1,
user_id = 3;

INSERT INTO bet
SET sum = 12599,
lot_id = 1,
user_id = 2;


-- Получение категорий
SELECT * FROM category;

-- Получение открытых лотов
SELECT creation_date, lot_name, category.cat_name, message, photo, start_price, step, expiration_date
FROM lot JOIN category ON category.id = lot.category_id
WHERE NOW() BETWEEN creation_date AND expiration_date;

-- Получение лота по id
SELECT lot_name, c.cat_name FROM lot l
JOIN category c ON l.category_id = c.id
WHERE l.id = 2;

-- Обновление название лота по его id
UPDATE lot
SET lot_name = 'Крутейшая Маска Oakley'
WHERE id = 6;

-- Получить список свежих ставок для лота
SELECT bet_date, sum FROM bet
JOIN lot ON bet.lot_id = lot.id
WHERE bet.lot_id = 1
ORDER BY bet_date DESC;
