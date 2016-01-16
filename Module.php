<?php

namespace humanized\clihelpers;

/**
 * Humanized CLI Helpers for Yii2
 * 
 * Provides several helper tools for dealing with Yii2 CLI Operations
 * 
 * @name Yii2 CLI Helpers Module Class 
 * @version 0.0.1
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-clihelpers
 * 
 */
class Module extends \yii\base\Module {

    public function init()
    {
        parent::init();
        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'humanized\clihelpers\commands';
        }
    }

}
