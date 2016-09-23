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
    public $dotwig;

    function __construct()
    {
        $this->user   = new User();
        $this->toview = new ToView();
        $this->dotwig = new DoTwig();
    }

    public function checkUser()
    {

        if (isset($_SESSION['id'])) {
            $this->dotwig->letsDoTwig();
        } else {
            if ($this->user->checkLoginPassword()) {
                $this->dotwig->letsDoTwig();
            } else {
                $this->showLogin();
            }
        }
    }
    public function showLogin()
    {
        if (isset($_SESSION['id'])) {
            $this->dotwig->letsDoTwig();
        } else {
            $a = new CreateCategoryTable();
            $a->up();
            $b = new CreateGoodsTable();
            $b->up();
            $this->toview->pLogin();
        }
    }

    public function logout()
    {
        if ($this->user->logoutUser()) {
            $this->showLogin();
        }
    }
}