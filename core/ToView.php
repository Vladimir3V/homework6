<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 16.09.16
 * Time: 22:54
 */
class ToView
{
    /**
     *
     */
    public function p404()
    {
        include_once 'view/404.html';
    }

    /**
     *
     */
    public function pLogin()
    {
        include_once 'view/login.html';
    }

    /**
     *
     */
    public function pRegister()
    {
        include_once 'view/register.html';
    }
}



