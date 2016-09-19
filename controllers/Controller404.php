<?php
/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 14.09.16
 * Time: 20:28
 */
class Controller404
{
    public function show404()
    {
        $er404 = new ToView();
        $er404->p404();
    }


}