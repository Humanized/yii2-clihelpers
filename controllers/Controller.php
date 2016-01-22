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
     * Common Console Application exit msg.
     * Should be modified during application runtime
     *  
     * @var int - 0 for Success, Else 
     */
    protected $_msg = NULL;

    /**
     *
     * @var type 
     */
    protected $_exludeAction = [];

    protected function exitMsg($fn = null)
    {
        $out = 'stdout';
        $status = 'SUCCESS';
        if ($this->_exitCode !== 0) {
            $out = 'stderr';
            $status = 'ERROR #' . $this->_exitCode;
        }
        $this->$out('[');
        $this->$out($status, (($this->_exitCode === 0 ? Console::FG_GREEN : Console::FG_RED)), Console::BOLD);
        $this->$out("]\t");
        $this->$out($this->_msg, Console::BG_BLUE);
        $this->stdout("\n");
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

    public function afterAction($action, $result)
    {
        if (FALSE === array_search($action->id, $this->_exludeAction)) {
            //Should remove in stable versions, but nice little fallback just in case
            $this->showInput();
            //Two newlines B4 program exit
            $this->exitMsg();
        }
        return parent::afterAction($action, $result);
    }

    /**
     * Import a CSV file
     * 
     * Imports a CSV table, in to a database provided an array of configuration options
     * 
     * - modelClassPath string
     * 
     * - columnMap array<int>=string Map of the columns
     * 
     * Protected as such methods can be overwritten and or extended
     * 
     * @param array<mixed> $config
     */
    protected function importCSV($config)
    {
        $fileName = $config['fileName'];
        $file = fopen($fileName, "r");
        while (!feof($file)) {
            $record = fgetcsv($file, 0, $config['delimter']);
            if (isset($record[0])) {
                $model = new $config['saveModel']($this->getRecordColumns($record, $config['attributeMap']));
                $model->save();
            } else {
                break;
            }
        }
    }

    protected function getRecordColumns($line, $columnMap)
    {
        
    }

}
