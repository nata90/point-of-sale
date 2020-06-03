<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setting_app".
 *
 * @property int $id
 * @property string $app_name
 * @property string $email
 * @property string|null $ip_address
 */
class SettingApp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_name', 'email'], 'required'],
            [['app_name'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'app_name' => Yii::t('app', 'Nama Aplikasi'),
            'email' => Yii::t('app', 'Email'),
            'ip_address' => Yii::t('app', 'Alamat IP'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SettingAppQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingAppQuery(get_called_class());
    }
}
