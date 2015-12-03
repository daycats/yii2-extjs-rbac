<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/12/3
 * Time: 1:14
 */

namespace wsl\rbac\actions;


use yii;
use yii\base\Action;

class ErrorAction extends Action
{
    public function run()
    {
        /* @var $controller \wsl\rbac\base\Controller */
        $controller = $this->controller;
        $exception = Yii::$app->errorHandler->exception;

        return $controller->error($exception->getMessage());
    }
}