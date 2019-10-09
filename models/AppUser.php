<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $authkey
 * @property string $accesstoken
 * @property string $name
 * @property int $id_group
 * @property int $aktif
 * @property string $created_at
 * @property string $updated_at
 */
class AppUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'id_group'], 'required'],
            [['id_group', 'aktif'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 64],
            [['password', 'authkey', 'accesstoken', 'name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'authkey' => 'Authkey',
            'accesstoken' => 'Accesstoken',
            'name' => 'Nama',
            'id_group' => 'Grup',
            'aktif' => 'Aktif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeValidate(){
        if(parent::beforeValidate()){
            $authkey = md5(time());
            $this->password = md5($this->password.$authkey);
            $this->authkey = $authkey;
            $this->accesstoken = '-';
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');

            return true;
        }

        return false;
    }


}
