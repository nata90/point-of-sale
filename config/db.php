<?php

$dbPath = getenv('POS_DB_PATH');
$dsn = ($dbPath !== false && $dbPath !== '')
    ? 'sqlite:' . $dbPath
    : 'sqlite:@app/data/db_pos_2.sqlite';

return [
    // 'class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=php7-mysql-local-1;dbname=db_pos',
    // 'username' => 'root',
    // 'password' => 'nataroot',
    // 'charset' => 'utf8',
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,


    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];