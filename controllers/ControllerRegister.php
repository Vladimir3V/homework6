<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 14.09.16
 * Time: 18:28
 */
class ControllerRegister
{
    public $user;
    public $toview;
    public $mail;
    public $dotwig;

    function __construct()//psr-2 у всех методов public private or protected
    {
        $this->user   = new User();
        $this->toview = new ToView();
        $this->mail   = new Mail();
        $this->dotwig = new DoTwig();
    }

    public function showRegister()
    {
        if (isset($_SESSION['id'])) {
            $this->dotwig->letsDoTwig();

        } else {
            $this->toview->pRegister();
        }
    }

    public function checkPassword()
    {
        if (isset($_SESSION['id'])) {
            $this->dotwig->letsDoTwig();
        } else {
            if ($this->user->addLoginPassword()) {
                $this->mail->sentMail();
                $this->dotwig->letsDoTwig();
            } else {
                $this->showRegister();
            }

        }
    }
}