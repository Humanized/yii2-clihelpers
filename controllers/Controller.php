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
    protected $_msg = NULL;

    /**
     * Prints output message as error
     */
    public function msgError()
    {
        $this->stdout("FAILED", Console::FG_RED, Console::BOLD);
        $this->stderr("\nGenerated Message: ");
        $this->stderr($this->_msg, Console::BG_BLUE);
    }

    public function msgSuccess()
    {
       
    }

    protected function msgStatus($fn=null)
    {
        $out = 'stdout';
        $status = 'SUCCESS';
        if ($this->_exitCode !== 0) {
            $out = 'stderr';
            $status = 'ERROR';
        }
        $this->$out('[');
        $this->$out($status, (($this->_exitCode !== 0 ? Console::FG_GREEN : Console::FG_RED)), Console::BOLD);
        $this->$out("]\t");
      
    }

    /**
     * Hides input
     * Linux Only
     * @todo Provide viable alternative for windows
     */
    protected function hideInput()
    {
        if (!Console::isRunningOnWindows()) {
            system('stty -echo');
        }
    }

    /**
     * Shows Input
     * Linux Only
     * @todo Provide viable alternative for windows
     */
    protected function showInput()
    {
        if (!Console::isRunningOnWindows()) {
            system('stty echo');
        }
    }

}
