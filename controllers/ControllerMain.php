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
//        $this->toview->pMain();
        try {
            include_once 'vendor/twig/twig/lib/Twig/Autoloader.php';
            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem('view');
            $twig = new Twig_Environment($loader);
            $template = $twig->loadTemplate('main.tmpl');
            echo $template->render(
                array(
                    'username' => 'ckent'
                )
            );
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }


    }

    public function addAvatar()
    {
        //так как ты везде создаешь экземпляр User
        //можешь его проинициализивароть в конструкторе и записать в свойстве
        //тем самым избавив себя от дублирования кода -- Понял, поправил
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