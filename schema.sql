CREATE DATABASE yeticave_db;

USE yeticave_db;

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE,
  name CHAR(64),
  category_id INT,
  description CHAR(255),
  photo CHAR(128),
  start_price INT,
  step INT,
  expiration_date DATE,
  author_id INT,
  winner_id INT
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(64)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(64),
  mail CHAR(64),
  password CHAR(64),
  avatar CHAR(128),
  contacts CHAR(255),
  created_lots_id INT,
  bets_id INT
);

CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE,
  sum INT,
  user_id INT,
  lot_id INT
);

CREATE UNIQUE INDEX mail ON users (mail);
CREATE INDEX lot_name ON lots (name);
CREATE INDEX lot_category ON lots (category_id);
