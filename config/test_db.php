<?php

$testDbPath = getenv('POS_TEST_DB_PATH');
$dsn = ($testDbPath !== false && $testDbPath !== '')
    ? 'sqlite:' . $testDbPath
    : 'sqlite::memory:';

return [
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,
];
