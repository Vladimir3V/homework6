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
     * DB_Operate constructor.
     * @param $host
     * @param $login
     * @param $password
     * @param $database
     */
//    function __construct()
//    {
//        $a = new Connection();
//        $this->db_host  = $a->host;
//        $this->db_login = $a->user;
//        $this->db_pass  = $a->password;
//        $this->db_name  = $a->dbace;
//    }

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

    /**
     * Создает подключение к базе данных
     */
    function connect()//psr-2 - к сожалению не могу понять в чем ошибка
    {
        $this->dbChekMake();
        $db = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->dbace
        );
        if (!$db->set_charset('utf8')) {
            print_r('Ошибка при загрузке набора символов utf8: %s\nFЫвфыыфвфыафы', $db->error);
        }

        if ($db->connect_errno) {
            exit('ошибка подключенияыывывывы');
        }
        return $db;
    }
}
