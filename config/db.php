<?php

return [
    // 'class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=php7-mysql-local-1;dbname=db_pos',
    // 'username' => 'root',
    // 'password' => 'nataroot',
    // 'charset' => 'utf8',
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:@app/data/db_pos_2.sqlite',
    

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];