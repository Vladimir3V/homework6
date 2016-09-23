<?php
/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 13.09.16
 * Time: 11:17
 */

class Route
{
    static function start()
    {
        // контроллер и действие по дефолту
        $controller_name = 'Login';
        $action_name = 'showLogin';

        // http://mvc/controller/action
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // получаем контроллер
        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }

        // получаем действие
        if (!empty($routes[2])) {
            $action_name = $routes[2];
        }

        $controller_name = 'Controller'.$controller_name;

        include_once 'vendor/autoload.php';
        include_once 'core/config.php';


        function fileAutoload($class_name)
        {
            if (file_exists('models/' . $class_name . '.php')) {
                include 'models/' . $class_name . '.php';
            } else {
                if (file_exists('core/' . $class_name . '.php')) {
                    include 'core/' . $class_name . '.php';
                }
            }
        }

        spl_autoload_register('fileAutoload');

        $controller_file = $controller_name.'.php';
        $controller_path = 'controllers/'.$controller_file;
        if (file_exists($controller_path)) {
            include $controller_path;
        } else {
            Route::errorPage404();
        }

        $controller = new $controller_name;
        $action = $action_name;

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            Route::errorPage404();
        }
    }


    /**
     *
     */
    static function errorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('Location:'.$host.'view/404.html');
    }
}
