<?php

namespace humanized\clihelpers\controllers;

/**
 * A Base Controller for building CLI to allow the import of one or multiple (big) CSV files in to a MySQL database.
 * 
 * By default, an index action is defined
 * 
 * @name Import CLI
 * @version 0.1
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-clihelpers
 * 
 * 
 */
class ImportController extends Controller {

    public $path = NULL;
    public $delimiter = ',';
    public $enclosure = '"';
    public $terminator = "\\n";
    public $start = 0;
    public $table = NULL;
    public $autobuild = TRUE;
    private $_pipeLine = ['validateInput', 'validateTable', 'constructTable', 'dumpFile'];
    private $_autobuild = FALSE;

    public function options($actionId)
    {
        return array_merge(
                parent::options($actionId), ['path', 'table', 'delimiter', 'enclosure', 'terminator', 'start', 'autobuild']);
    }

    public function actionIndex()
    {
        $stepCounter = count($this->_pipeLine);
        $i = 0;

        foreach ($this->_pipeLine as $step) {
            $this->$step();
            if ($this->_exitCode !== 0) {
                return $this->_exitCode;
            }
            if ($i != $stepCounter - 1) {

                $this->printStatus();
            }
            $i++;
        }
        return $this->_exitCode;
    }

    protected function validateInput()
    {

        if (!isset($this->path)) {
            $this->_msg = 'Path not set';
            $this->_exitCode = 100;
            return;
        }
        $this->_msg .= 'Fetching File ' . $this->path;
        if (!file_exists($this->path)) {
            $this->_msg.= '- Not Found';
            $this->_exitCode = 102;
            return;
        }
    }

    protected function validateTable()
    {
        $this->_msg = 'Searching for DB table';
        if (!isset($this->table)) {
            $this->table = 'import_' . time();
        }
        $sql = "SHOW TABLES LIKE '$this->table'";
        if (count(\Yii::$app->db->createCommand($sql)->queryColumn()) == 0) {
            $this->_exitCode = !$this->autobuild ? 701 : 0;
            $this->_msg.=" - Not Found";
            if (!$this->autobuild) {
                $this->_msg.=" (Exit with autobuild disabled)";
                return;
            }
            $this->_msg.=" (Continue with autobuild enabled)";
            $this->_autobuild = TRUE;
        }
    }

    protected function constructTable()
    {
        $this->_msg = 'Auto-Generating Table: ' . $this->table;
        if (!$this->_autobuild) {
            $this->_msg .= " - Skipped";
        }
        if ($this->_autobuild) {
            $file = fopen($this->path, 'r', $this->delimiter);
            $columnNames = fgetcsv($file);
            fclose($file);
            $this->_constructTable($columnNames);
        }
    }

    protected function _constructTable($columnNames)
    {
        $columns = [];
        foreach ($columnNames as $columnName) {
            $columns[$columnName] = 'VARCHAR(255) DEFAULT NULL';
        }
        try {
            \Yii::$app->db->createCommand()->createTable($this->table, $columns)->execute();
            \Yii::$app->db->createCommand()->addPrimaryKey('pk_' . $this->table, $this->table, $columnNames[0])->execute();
        } catch (\Exception $ex) {
            $this->_msg = $ex->getMessage();
            $this->_exitCode = 700;
        }
    }

    protected function dumpFile()
    {
        \Yii::$app->db->attributes[\PDO::MYSQL_ATTR_LOCAL_INFILE] = true;
        $this->_msg = 'Dumping file contents to  ' . $this->table . ' (Handling large files may take a while)';
        $this->printWarning();
        $this->_msg = 'File Dumped';
        $sql = "LOAD DATA INFILE '$this->path' 
        REPLACE INTO TABLE $this->table FIELDS TERMINATED BY '$this->delimiter' ENCLOSED BY '$this->enclosure' 
        LINES TERMINATED BY '$this->terminator' IGNORE 1 LINES";
//        echo "\n\n$sql\n\n";

        $pass = \Yii::$app->db->createCommand($sql)->execute();
        if (!$pass) {
            $this->_exitCode = 780;
          
        }
    }

    protected function _dumpTable()
    {
        
    }

}
