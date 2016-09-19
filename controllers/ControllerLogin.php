<?php
//session_start();
/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 14.09.16
 * Time: 18:27
 */
class ControllerLogin
{
    public $user;
    public $toview;

    function __construct()
    {
        $this->user   = new User();
        $this->toview = new ToView();
    }

    public function checkUser()
    {
        if ($this->user->checkLoginPassword()) {
            $this->showMain();
        } else {
            $this->showLogin();
        }
    }
    public function showLogin()
    {
        if (isset($_SESSION['id'])) {
            $this->showMain();
        } else {
            $this->toview->pLogin();
        }
    }

    public function showMain()
    {
        $this->toview->pMain();
    }

    public function logout()
    {
        if ($this->user->logoutUser()) {
            $this->showLogin();
        }
    }
}