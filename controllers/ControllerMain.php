<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 14.09.16
 * Time: 18:29
 */
class ControllerMain
{
    public $user;
    public $toview;
    public $dotwig;

    function __construct()//psr-2
    {
        $this->user   = new User();
        $this->toview = new ToView();
        $this->dotwig = new DoTwig();

    }


    public function showMain()
    {
        $this->dotwig->letsDoTwig();
    }

    public function addAvatar()
    {
        $this->user->addAvatar();
        $this->showMain();
    }

    public function addPicture()
    {
        $this->user->addPicture();
        $this->showMain();

    }

    public function addUserInfo()
    {
        $this->user->addNameAgeAbout();
        $this->showMain();

    }

    public function getPhotoList()
    {
        $this->user->getAllPhoto();
        $this->showMain();
    }

    public function getUserList()
    {
        $this->user->getAllUser();
        $this->showMain();
    }

    public function delUser()
    {
        if ($this->user->deletUser()) {
            $this->toview->pLogin();
        }
    }

}