<?php

namespace humanized\clihelpers\components;

class Migration extends \yii\db\Migration {

    protected $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function createLookupTable($name)
    {
        $this->createTable($name, [
            'id' => 'pk',
            'name' => 'VARCHAR(45) NOT NULL',
            'UNIQUE INDEX name_UNIQUE (name ASC)',
                ], $this->tableOptions);

        return true;
    }

}
