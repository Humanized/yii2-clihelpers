<?php

namespace humanized\clihelpers\components;

/**
 * A collection of static helper functions to implement the user management 
 */
class DataTable
{

    public $modelClass;
    public $data = [];

    public function unloadCondtion($record)
    {
        return ['name' => $record['name']];
    }

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

    public static function unload()
    {

        $class = get_called_class();

        echo 'Loading data from file: ' . "$class \n";
        $instance = new $class();
        foreach ($instance->data as $record) {



            try {
                $unloadCondition = $instance->unloadCondition($record);
                $class = $instance->modelClass;
                $model = $class::findOne($unloadCondition);
                if (isset($model)) {
                    echo 'dropping record:' . var_dump($unloadCondition);
                    $model->delete();
                }
            } catch (\Exception $exc) {
                echo $exc->getMessage();
            }
        }
        echo 'Complete';
    }

}
