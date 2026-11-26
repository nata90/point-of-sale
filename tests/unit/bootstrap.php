<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_ENV_TEST') or define('YII_ENV_TEST', true);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/support/ArraySession.php';
require_once __DIR__ . '/support/TestSchema.php';
require_once __DIR__ . '/BaseTestCase.php';

$config = require __DIR__ . '/../../config/test.php';
new yii\web\Application($config);

tests\unit\support\TestSchema::init(Yii::$app->db);
