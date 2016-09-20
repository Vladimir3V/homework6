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

    function __construct()
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
        if ($this->user->addAvatar()) {
            $this->showMain();
        }
    }

    public function addPicture()
    {
        if ($this->user->addPicture()) {
            $this->showMain();
        }
    }

    public function addUserInfo()
    {
        if ($this->user->addNameAgeAbout()) {
            $this->showMain();
        }
    }

    public function getPhotoList()
    {
        if ($this->user->getAllPhoto()) {
            $this->showMain();
        }
    }

    public function getUserList()
    {
        if ($this->user->getAllUser()) {
            $this->showMain();
        }
    }

    public function delUser()
    {
        if ($this->user->deletUser()) {
            $this->toview->pLogin();
        }
    }

}