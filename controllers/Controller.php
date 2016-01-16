<?php

namespace humanized\clihelpers\controllers;

/**
 * 
 */
class Controller extends \yii\console\Controller {

    public static function msgError($msg)
    {
        echo $msg;
    }

    public static function msgSuccess()
    {
        echo 'OK';
    }

}
