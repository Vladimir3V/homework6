<?php
//require_once 'vendor/autoload.php';
//require_once 'vendor/twig/twig/lib/Twig/Autoloader.php';
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
}