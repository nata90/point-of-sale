<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kode_generate".
 *
 * @property int $id
 * @property string $nama_transaksi
 * @property string $nama_alias
 * @property int $urutan
 */
class KodeGenerate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kode_generate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_transaksi', 'nama_alias'], 'required'],
            [['urutan'], 'integer'],
            [['nama_transaksi'], 'string', 'max' => 200],
            [['nama_alias'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama_transaksi' => Yii::t('app', 'Nama Transaksi'),
            'nama_alias' => Yii::t('app', 'Nama Alias'),
            'urutan' => Yii::t('app', 'Urutan'),
        ];
    }
}
