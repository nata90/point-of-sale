<?php

namespace tests\unit\models;

use app\models\AppUser;
use app\models\LoginForm;
use tests\unit\BaseTestCase;
use Yii;

class LoginFormTest extends BaseTestCase
{
    private function createUser($username = 'admin', $password = 'admin123')
    {
        $user = new AppUser([
            'username' => $username,
            'password' => $password,
            'name' => 'Admin Test',
            'id_group' => 1,
            'aktif' => 1,
        ]);
        $this->assertTrue($user->save());
        return $user;
    }

    public function testLoginRequiresUsernameAndPassword()
    {
        $model = new LoginForm();

        $this->assertFalse($model->validate());
        $this->assertArrayHasKey('username', $model->errors);
        $this->assertArrayHasKey('password', $model->errors);
    }

    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        $this->assertFalse($model->login());
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertArrayHasKey('password', $model->errors);
    }

    public function testLoginWrongPassword()
    {
        $this->createUser('kasir', 'rahasia');

        $model = new LoginForm([
            'username' => 'kasir',
            'password' => 'salah',
        ]);

        $this->assertFalse($model->login());
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertArrayHasKey('password', $model->errors);
    }

    public function testLoginCorrect()
    {
        $user = $this->createUser('kasir', 'rahasia');

        $model = new LoginForm([
            'username' => 'kasir',
            'password' => 'rahasia',
            'rememberMe' => true,
        ]);

        $this->assertTrue($model->login());
        $this->assertFalse(Yii::$app->user->isGuest);
        $this->assertEmpty($model->errors);
        $this->assertEquals($user->id, Yii::$app->user->id);
    }
}
