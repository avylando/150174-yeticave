-- Добавление категорий
INSERT INTO category (name)
VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

-- Добавление пользователей
INSERT INTO user (name, email, password, contacts)
VALUES ('Игнат', 'ignat.v@gmail.com', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', 'phone: 89653426190'),
('Леночка', 'kitty_93@li.ru', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', 'Adress: Gagarin st. 18'),
('Руслан', 'warrior07@mail.ru', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', 'mobile: 89222310241. home: 7291082');

-- Добавление лотов
INSERT INTO lot (name, category_id, message, photo, start_price, step, expiration_date, author_user_id)
VALUES ('2014 Rossignol District Snowboard', 1, 'Борд', 'img/lot-1.jpg', 12299, 100, '2018-03-21 00:00:00', 1),
('DC Ply Mens 2016/2017 Snowboard', 1, 'Snowboard', 'img/lot-2.jpg', 159999, 10000, '2018-03-30 00:00:00', 2),
('Крепления Union Contact Pro 2015 года размер L/XL', 2, 'Почти новые крепления', 'img/lot-3.jpg', 8000, 500, '2018-03-23 00:00:00', 2),
('Ботинки для сноуборда DC Mutiny Charocal', 3, 'Клевые ботинки', 'img/lot-4.jpg', 10999, 500, '2018-05-09 00:00:00', 3),
('Куртка для сноуборда DC Mutiny Charocal', 4, 'Модная куртка', 'img/lot-5.jpg', 7500, 200, '2018-03-11 00:00:00', 1),
('Маска Oakley Canopy', 5, 'Подходит для любого времени суток', 'img/lot-6.jpg', 5400, 100, '2018-04-21 00:00:00', 3);

-- Добавление ставок
INSERT INTO bet (sum, lot_id, user_id)
VALUES (11499, 1, 2), (12099, 1, 3), (8500, 3, 1);

-- Получение категорий
SELECT * FROM category;

-- Получение открытых лотов
SELECT creation_date, lot.name, category.name, message, photo, start_price, step, expiration_date
FROM lot JOIN category ON category.id = lot.category_id
WHERE NOW() BETWEEN creation_date AND expiration_date;

-- Получение лота по id
SELECT l.name, c.name FROM lot l
JOIN category c ON l.category_id = c.id
WHERE l.id = 2;

-- Обновление название лота по его id
UPDATE lot
SET lot.name = 'Крутейшая Маска Oakley'
WHERE id = 6;

-- Получить список свежих ставок для лота
SELECT lot.name, bet.date, sum FROM bet
JOIN lot ON bet.lot_id = lot.id
WHERE bet.lot_id = 1
ORDER BY bet.date DESC, sum DESC;
