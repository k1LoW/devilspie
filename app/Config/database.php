<?php
class DATABASE_CONFIG {

    public $default = array(
        'datasource' => 'Database/Sqlite',
        'persistent' => false,
        'database' => DB_PATH,
    );

    public $test = array(
        'datasource' => 'Database/Sqlite',
        'persistent' => false,
        'database' => TEST_DB_PATH,
    );
}
