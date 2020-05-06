<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "urutan_transaksi".
 *
 * @property int $id
 * @property string $nama_transaksi
 * @property int $urutan
 * @property string $tgl_transaksi
 */
class UrutanTransaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'urutan_transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_transaksi', 'tgl_transaksi'], 'required'],
            [['urutan'], 'integer'],
            [['tgl_transaksi'], 'safe'],
            [['nama_transaksi'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_transaksi' => 'Nama Transaksi',
            'urutan' => 'Urutan',
            'tgl_transaksi' => 'Tgl Transaksi',
        ];
    }

    public static function getUrutanTransaksi(){
        
    }
}
