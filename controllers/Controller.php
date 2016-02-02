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
 * @version 0.1
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-clihelpers
 * 
 */
class Controller extends \yii\console\Controller {

    protected $_status = 'SUCCESS';

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
    public $preventDefault = FALSE;

    protected function printStatus($fn = null)
    {
        $out = 'stdout';
        if ($this->_exitCode !== 0) {
            $out = 'stderr';
            $this->_status = 'ERROR #' . $this->_exitCode;
        }
        $this->$out('[');
        $this->$out($this->_status, (($this->_exitCode === 0 ? Console::FG_GREEN : Console::FG_RED)), Console::BOLD);
        $this->$out("]\t");
        $this->$out($this->_msg, Console::BG_BLUE);
        $this->stdout("\n");
    }

    protected function printWarning($fn = NULL)
    {
        $this->printStatus($fn);
        $this->_exitCode = 0;
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
            if (!$this->preventDefault) {
                $this->printStatus();
            }
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
    protected function importCSV($config, $fn = NULL)
    {
        $fileName = $config['fileName'];
        $file = fopen($fileName, "r");
        while (!feof($file)) {
            $record = fgetcsv($file, 0, $config['delimiter']);

            if (isset($record[0])) {

                //Parse attribute map
                $attributes = $this->parseAttributeMap($config['attributeMap'], $record);
                isset($fn) ? $fn($attributes, $config) : NULL;
                var_dump($attributes);
                $model = new $config['saveModel']();
                $model->setAttributes($attributes);
                $model->save();
            } else {
                break;
            }
        }
    }

    protected function parseAttributeMap($map, $record)
    {
        foreach ($map as $key => $value) {
            $map[$value] = $record[$key];
            unset($map[$key]);
        }
        return $map;
    }

}
