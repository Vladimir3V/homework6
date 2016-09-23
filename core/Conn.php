<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 16.09.16
 * Time: 14:35
 */
class Conn
{
    public $host = 'localhost';
    public $user = 'root';
    public $password = 'root';
    public $dbace = 'uzzerz';


    /**
     * ПРоверяет есть ли база, если нет создает
     */
    public function dbChekMake()
    {
        $mysqli = new mysqli($this->host, $this->user, $this->password);
        if ($mysqli->connect_errno) {
            exit('Не удалось подключиться к MySQL: ' . $mysqli->connect_error);
        } else {
            $db = new mysqli(
                $this->host,
                $this->user,
                $this->password,
                $this->dbace
            );
            if ($db->connect_errno) {
                $mysqli->query("CREATE DATABASE IF NOT EXISTS $this->dbace");
                $mysqli->select_db($this->dbace);
                $mysqli->query(
                    "CREATE TABLE users (
                             id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                             username VARCHAR(50),
                             password VARCHAR(50),
                             name VARCHAR(50),
                             age INT (3),
                             about VARCHAR(1000),
                             avatar VARCHAR(50),
                             ip VARCHAR (20)
                             )
                             DEFAULT CHARSET = utf8
                             COLLATE=utf8_unicode_ci
                             AUTO_INCREMENT = 1"
                );
                $mysqli->query(
                    "CREATE TABLE photos (
                            id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
                            user_id INT UNSIGNED,
                            file VARCHAR(200)
                        )
                        DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1"
                );
            }
        }
    }
}

