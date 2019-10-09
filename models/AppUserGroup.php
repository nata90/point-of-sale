<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_group".
 *
 * @property int $id_group
 * @property string $group_name
 * @property int $aktif
 * @property string $created_at
 * @property string $updated_at
 */
class AppUserGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_name', 'created_at'], 'required'],
            [['aktif'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['group_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_group' => 'Id Group',
            'group_name' => 'Group Name',
            'aktif' => 'Aktif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
