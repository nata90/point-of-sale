<?php

namespace tests\unit\models;

use app\models\AppUser;
use tests\unit\BaseTestCase;

class AppUserTest extends BaseTestCase
{
    public function testValidationRequiresUsernamePasswordAndGroup()
    {
        $user = new AppUser();
        // beforeValidate() hashes empty password, so password becomes non-empty md5 string;
        // username and id_group remain required.
        $this->assertFalse($user->validate());
        $this->assertArrayHasKey('username', $user->errors);
        $this->assertArrayHasKey('id_group', $user->errors);
    }

    public function testSaveHashesPasswordAndSetsAuthMetadata()
    {
        $plainPassword = 'secret123';
        $user = new AppUser([
            'username' => 'kasir1',
            'password' => $plainPassword,
            'name' => 'Kasir Satu',
            'id_group' => 1,
            'aktif' => 1,
        ]);

        $this->assertTrue($user->save());
        $this->assertNotEmpty($user->id);
        $this->assertNotSame($plainPassword, $user->password);
        $this->assertSame(32, strlen($user->authkey));
        $this->assertSame('-', $user->accesstoken);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->updated_at);

        $this->assertTrue($user->validatePassword($plainPassword, $user->authkey));
        $this->assertFalse($user->validatePassword('wrong-pass', $user->authkey));
        $this->assertFalse($user->validatePassword($plainPassword, 'wrong-authkey'));
    }

    public function testIdentityLookupAndAuthKeyValidation()
    {
        $user = new AppUser([
            'username' => 'admin_pos',
            'password' => 'admin123',
            'name' => 'Administrator',
            'id_group' => 1,
            'aktif' => 1,
        ]);
        $this->assertTrue($user->save());

        $foundById = AppUser::findIdentity($user->id);
        $this->assertNotNull($foundById);
        $this->assertSame('admin_pos', $foundById->username);
        $this->assertEquals($user->id, $foundById->getId());
        $this->assertSame($user->authkey, $foundById->getAuthKey());
        $this->assertTrue($foundById->validateAuthKey($user->authkey));
        $this->assertFalse($foundById->validateAuthKey('invalid-key'));

        $foundByUsername = AppUser::findByUsername('admin_pos');
        $this->assertNotNull($foundByUsername);
        $this->assertEquals($user->id, $foundByUsername->id);

        $this->assertNull(AppUser::findByUsername('tidak_ada'));
        $this->assertNull(AppUser::findIdentity(999999));

        // Verify findIdentityByAccessToken queries the lowercase accesstoken column
        $user->updateAttributes(['accesstoken' => 'tok-abc-123']);
        $foundByToken = AppUser::findIdentityByAccessToken('tok-abc-123');
        $this->assertNotNull($foundByToken);
        $this->assertEquals($user->id, $foundByToken->id);
        $this->assertNull(AppUser::findIdentityByAccessToken('tok-tidak-ada'));
    }
}
