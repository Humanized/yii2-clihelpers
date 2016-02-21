<?php

namespace humanized\clihelpers\components;

/**
 * A collection of static helper functions to implement the user management 
 */
class DataTable {

    public $modelClass;
    public $data = [];

    public static function load()
    {

        $class = get_called_class();

        echo 'Loading data from file: ' . "$class \n";
        $instance = new $class();
        foreach ($instance->data as $record) {

            $model = new $instance->modelClass($record);
            try {
                $model->save();
            } catch (\Exception $exc) {
                echo $exc->getMessage();
            }
        }
        echo 'Complete';
    }

}
