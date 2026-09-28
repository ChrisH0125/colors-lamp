CREATE DATABASE IF NOT EXISTS colors_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE colors_app;

CREATE TABLE IF NOT EXISTS Users (
    ID INT NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL DEFAULT '',
    LastName VARCHAR(50) NOT NULL DEFAULT '',
    Login VARCHAR(50) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    PRIMARY KEY (ID),
    UNIQUE KEY users_login_unique (Login)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS Colors (
    ID INT NOT NULL AUTO_INCREMENT,
    Name VARCHAR(50) NOT NULL,
    UserID INT NOT NULL,
    PRIMARY KEY (ID),
    KEY colors_user_id_index (UserID),
    CONSTRAINT colors_user_id_foreign
        FOREIGN KEY (UserID) REFERENCES Users (ID)
        ON DELETE CASCADE
) ENGINE = InnoDB;

