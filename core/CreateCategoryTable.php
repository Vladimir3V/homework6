<?php
/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 23.09.16
 * Time: 19:55
 */
require 'vendor/autoload.php';
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('category', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('category')->unique();
        });
    }

    public function down()
    {
        Schema::drop('category');
    }
}