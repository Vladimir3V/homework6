<?php
//session_start();
///**
// * Created by PhpStorm.
// * User: vladimirvahrusev
// * Date: 13.09.16
// * Time: 11:05
// */
//
//ini_set('display_errors', 1);
//require_once 'core/route.php';
//
//Route::start();


require_once 'vendor/autoload.php';
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface;

$app = new \Slim\App;

$dsn = 'mysql:host=localhost;dbname=uzzerz;charset=utf8';
$usr = 'root';
$pwd = 'root';

$pdo = new \Slim\PDO\Database($dsn, $usr, $pwd);

$app->get('/', function($req, $resp, $options) {
    $resp->write('Hello');
});

$app->get('/user/{name}', function($req, $resp, $options) {
    $name = $req->getAttribute('name');
    $resp->write('Hello, '. $name);
});

$app->post('/', function($req, $resp, $options) {
    $resp->write('hifrompost');
});

$app->get('/user', function (ServerRequestInterface $req, \Psr\Http\Message\ResponseInterface $resp) use ($pdo) {
    $selectStatment = $pdo->select()
        ->from('users');
    $stmt = $selectStatment->execute();
    $data = $stmt->fetchAll();
    return $resp->withJson($data)->withHeader('Content-Type', 'application/json');
});

$app->run();