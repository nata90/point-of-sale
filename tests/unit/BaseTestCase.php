<?php

namespace tests\unit;

use PHPUnit\Framework\TestCase;
use tests\unit\support\TestSchema;
use Yii;

abstract class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        TestSchema::reset(Yii::$app->db);
        Yii::$app->session->removeAll();
        Yii::$app->user->logout(false);
        Yii::$app->request->setQueryParams([]);
        Yii::$app->request->setBodyParams([]);
    }

    protected function tearDown(): void
    {
        Yii::$app->user->logout(false);
        Yii::$app->session->removeAll();
        parent::tearDown();
    }
}
