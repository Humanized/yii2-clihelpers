<?php

namespace humanized\clihelpers\controllers;

use yii\helpers\Console;

/**
 * An extended Console Controller Base Class
 * 
 * It provides several common operations used in the yii2-user and yii2-rbac CLI Extensions. 
 * Though it can be configured to facilitate more convenient general-purpose development. 
 * 
 * 
 * @name CLI Extended Controller 
 * @version 0.0.1
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-clihelpers
 * 
 */
class Controller extends \yii\console\Controller {

    /**
     * Common Console Application exit code.
     * Should be modified whenever the command provokes an erroneous exit code
     *  
     * @var int - 0 for Success, Else 
     */
    protected $_exitCode = 0;

    /**
     * Common Console Application error msg.
     * Should be modified whenever the command provokes an erroneous exit code
     *  
     * @var int - 0 for Success, Else 
     */
    protected $_error = NULL;

    public function msgError()
    {
        $this->stdout("FAILED", Console::FG_RED, Console::BOLD);
        $this->stderr("\nGenerated Message: ");
        $this->stderr($this->_error, Console::BG_BLUE);
    }

    public function msgSuccess()
    {
        $this->stdout("SUCCESS", Console::FG_GREEN, Console::BOLD);
    }
   
}
