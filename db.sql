CREATE DATABASE buysell;
use buysell;

/* пользователи */
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password CHAR(64) NOT NULL,
    /* если moderator = 1, то пользователь модератор */
    moderator BOOLEAN NOT NULL DEFAULT 0,
    avatar VARCHAR(255),
    date_add TIMESTAMP DEFAULT NOW()
);

/* категории */
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(12) NOT NULL
);

/* объявления */
CREATE TABLE ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    /* если status = 1, то объявление должно быть опубликовано на сайте; если = 0, то должно быть скрыто с сайта */
    status BOOLEAN NOT NULL DEFAULT 1,
    user_id INT NOT NULL,
    header VARCHAR(100) NOT NULL,
    photo VARCHAR(255),
    price INT NOT NULL,
    type enum('buy', 'sell') NOT NULL,
    text VARCHAR(1000) NOT NULL,
    date_add TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES user (id)
);

/* комментарии */
CREATE TABLE comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ticket_id INT NOT NULL,
    text VARCHAR(255) NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES user (id),
    FOREIGN KEY (ticket_id) REFERENCES ticket (id)
);

/* таблица-посредник объявленния-категории */
CREATE TABLE ticket_category (
    ticket_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (ticket_id, category_id),
    FOREIGN KEY (ticket_id) REFERENCES ticket (id),
    FOREIGN KEY (category_id) REFERENCES category(id)
);

