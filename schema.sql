CREATE DATABASE yeticave_db;

USE yeticave_db;

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(64) NOT NULL,
  mail CHAR(64) NOT NULL,
  password CHAR(64) NOT NULL,
  contacts CHAR(255) NOT NULL,
  avatar CHAR(128) DEFAULT 'img/user.jpg'
);

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(64) NOT NULL
);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATETIME NOT NULL DEFAULT NOW(),
  name CHAR(64) NOT NULL,
  category_id INT NOT NULL,
  message CHAR(255) NOT NULL,
  photo CHAR(128) NOT NULL,
  start_price INT NOT NULL,
  step INT NOT NULL,
  expiration_date DATETIME NOT NULL,
  author_user_id INT NOT NULL,
  winner_user_id INT NOT NULL,
  CONSTRAINT FK_LotCategory FOREIGN KEY (category_id) REFERENCES category(id),
  CONSTRAINT FK_LotAuthor FOREIGN KEY (author_user_id) REFERENCES user(id),
  CONSTRAINT FK_LotWinner FOREIGN KEY (winner_user_id) REFERENCES user(id)
);

CREATE TABLE bet (
  id INT AUTO_INCREMENT PRIMARY KEY,
  betdate DATETIME NOT NULL DEFAULT NOW(),
  sum INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,
  CONSTRAINT FK_BetUser FOREIGN KEY (user_id) REFERENCES user(id),
  CONSTRAINT FK_BetLot FOREIGN KEY (lot_id) REFERENCES lot(id)
);

CREATE UNIQUE INDEX mail ON user (mail);
CREATE INDEX lot_name ON lot (name);
CREATE INDEX lot_category ON lot (category_id);
