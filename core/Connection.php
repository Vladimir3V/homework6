<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 16.09.16
 * Time: 14:35
 */
class Connection
{
    public $host = 'localhost';
    public $user = 'root';
    public $password = 'root';
    public $dbace = 'users';

    /**
     * Создает подключение к базе данных
     */
    function connect()//psr-2 - к сожалению не могу понять в чем ошибка
    {
        $db = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->dbace
        );
        if (!$db->set_charset('utf8')) {
            print_r('Ошибка при загрузке набора символов utf8: %s\n', $db->error);
        }

        if ($db->connect_errno) {
            exit('ошибка подключения');
        }
        return $db;
    }

}
