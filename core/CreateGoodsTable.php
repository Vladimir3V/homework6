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

class CreateGoodsTable extends Migration
{
    public function up()
    {
        Schema::create('goods', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('article');
            $table->string('description');
            $table->string('price');
        });
    }

    public function down()
    {
        Schema::drop('goods');
    }
}

