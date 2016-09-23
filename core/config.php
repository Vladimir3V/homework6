<?php
/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 20.09.16
 * Time: 14:40
 */

require 'vendor/autoload.php';

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection
    ([
    'driver' => 'mysql',
    'host'  => 'localhost',
    'database' => 'uzzerz',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
    ]);


$capsule->setAsGlobal();
$capsule->bootEloquent();



class Users extends Model
{
    protected $table   = 'users';
    public $timestamps = false;
}

class Photos extends Model
{
    protected $table   = 'photos';
    public $timestamps = false;
}

