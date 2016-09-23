<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 20.09.16
 * Time: 23:41
 */
class GumpCheck
{
    /**
     * @return bool
     */
    public function checkPostReg()
    {
        $_POST['ip'] = $_SERVER['REMOTE_ADDR'];

        $result = GUMP::is_valid(
            $_POST, [
            'registerLogin' => 'required|min_len,5',
            'ip' => 'valid_ip'
            ]
        );

        if ($result === true) {
            return true;
        } else {
            echo 'Логин должен быть минимум 5 символов';
            return false;
        }
    }

    /**
     * @return bool
     */
    public function checkPostData()
    {
        $result= GUMP::is_valid(
            $_POST, [
            'About' => 'required|min_len,50'
            ]
        );

        if ($result === true && $_POST['Age']>10 && $_POST['Age']<100) {
            return true;
        } else {
            echo 'О себе не менее 50 символов и возраст не менее 10 и не более 100';
            return false;
        }
    }




}