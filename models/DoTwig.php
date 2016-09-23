<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 19.09.16
 * Time: 14:25
 */
class DoTwig
{
    public function letsDoTwig()
    {
        try {
            $loader = new Twig_Loader_Filesystem('view');
            $twig = new Twig_Environment($loader);
            $template = $twig->loadTemplate('main.tmpl');
            echo $template->render(
                array(
                    'username' => $_SESSION['username'],
                    'password' => $_SESSION['password'],
                    'name'     => $_SESSION['name'],
                    'age'      => $_SESSION['age'],
                    'about'    => $_SESSION['about'],
                    'avatar'   => $_SESSION['avatar']

                )
            );
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
}