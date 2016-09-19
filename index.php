<?php
session_start();
/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 13.09.16
 * Time: 11:05
 */

ini_set('display_errors', 1);
require_once 'core/route.php';

Route::start();

// подгружаем и активируем авто-загрузчик Twig-а

//require_once 'vendor/twig/twig/lib/Twig/Autoloader.php';
//
//Twig_Autoloader::register();
//
//try {
//
//  // указывае где хранятся шаблоны
//
//  $loader = new Twig_Loader_Filesystem('templates');
//
//  // инициализируем Twig
//
//  $twig = new Twig_Environment($loader);
//
//  // подгружаем шаблон
//
//  $template = $twig->loadTemplate('thanks.tmpl');
//
//
//  // передаём в шаблон переменные и значения
//
//  // выводим сформированное содержание
//
//  echo $template->render(array(
//
//    'name' => 'Clark Kent',
//
//    'username' => 'ckent',
//
//    'password' => 'krypt0n1te',
//
//  ));
//
//} catch (Exception $e) {
//  die ('ERROR: ' . $e->getMessage());
//
//}
//
//try {
//    require_once 'vendor/twig/twig/lib/Twig/Autoloader.php';
//    Twig_Autoloader::register();
//    $loader = new Twig_Loader_Filesystem('view');
//    $twig = new Twig_Environment($loader);
//    $template = $twig->loadTemplate('main.tmpl');
//    echo $template->render(
//        array(
//            'username' => 'ckent'
//        )
//    );
//} catch (Exception $e) {
//    die ('ERROR: ' . $e->getMessage());
//}
