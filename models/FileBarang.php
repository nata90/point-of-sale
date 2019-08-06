<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file_barang".
 *
 * @property int $id
 * @property string $kd_barang
 * @property string $nama_barang
 * @property int $harga_beli
 * @property int $harga_jual
 * @property int $qty
 * @property int $aktif
 */
class FileBarang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_barang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_barang', 'nama_barang'], 'required'],
            [['harga_beli', 'harga_jual', 'qty', 'aktif'], 'integer'],
            [['kd_barang'], 'string', 'max' => 30],
            [['nama_barang'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kd_barang' => 'Kd Barang',
            'nama_barang' => 'Nama Barang',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'qty' => 'Qty',
            'aktif' => 'Aktif',
        ];
    }
}
