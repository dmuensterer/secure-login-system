/*
** Create Database and user
*/

/*CREATE DATABASE `auth`;
CREATE USER 'auth_user' IDENTIFIED BY 'mypassword';
GRANT ALL PRIVILEGES ON `auth`.* TO 'auth_user'@localhost IDENTIFIED BY 'mypassword';
FLUSH PRIVILEGES;
*/

CREATE TABLE Accounts (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    account_data varchar(2000)DEFAULT '' NOT NULL
);


CREATE TABLE Users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(2000) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verification VARCHAR(2000),
    email_verified BIT(1) NOT NULL DEFAULT 0,
    passwordVerification VARCHAR(2000),
    password_reset_valid_until VARCHAR(2000),
    account int(11),
    FOREIGN KEY (account) REFERENCES Accounts(id)
);
