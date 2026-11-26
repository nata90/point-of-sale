<?php

namespace tests\unit\models;

use app\models\User;
use tests\unit\BaseTestCase;

class UserTest extends BaseTestCase
{
    public function testFindUserById()
    {
        $user = User::findIdentity(100);
        $this->assertNotNull($user);
        $this->assertSame('admin', $user->username);

        $this->assertNull(User::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        $user = User::findIdentityByAccessToken('100-token');
        $this->assertNotNull($user);
        $this->assertSame('admin', $user->username);

        $this->assertNull(User::findIdentityByAccessToken('non-existing'));
    }

    public function testFindUserByUsername()
    {
        $this->assertNotNull(User::findByUsername('admin'));
        $this->assertNull(User::findByUsername('not-admin'));
    }

    public function testValidateUser()
    {
        $user = User::findByUsername('admin');
        $this->assertTrue($user->validateAuthKey('test100key'));
        $this->assertFalse($user->validateAuthKey('test102key'));

        $this->assertTrue($user->validatePassword('admin'));
        $this->assertFalse($user->validatePassword('123456'));
    }
}
