<?php

namespace humanized\clihelpers\controllers;

use yii\helpers\Console;

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
        $this->stdout("Success", Console::FG_GREEN, Console::BOLD);
    }

}
