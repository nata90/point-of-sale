<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $nama_supplier
 * @property string $alamat_supplier
 * @property string $no_telp
 * @property string $cp
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_telp', 'cp'], 'required'],
            [['nama_supplier', 'alamat_supplier', 'no_telp', 'cp'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama_supplier' => Yii::t('app', 'Nama Supplier'),
            'alamat_supplier' => Yii::t('app', 'Alamat Supplier'),
            'no_telp' => Yii::t('app', 'No Telp'),
            'cp' => Yii::t('app', 'Contact Person'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SupplierQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SupplierQuery(get_called_class());
    }
}
