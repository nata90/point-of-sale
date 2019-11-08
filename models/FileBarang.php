<?php

namespace app\models;

use Yii;
use app\models\KodeGenerate;

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
            [['kd_barang', 'nama_barang', 'harga_beli', 'harga_jual',], 'required'],
            [['harga_beli', 'harga_jual', 'aktif', 'stok'], 'integer'],
            [['kd_barang'], 'string', 'max' => 30],
            [['nama_barang'], 'string', 'max' => 200],
            [['lokasi'], 'string', 'max' => 100],
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
            'lokasi' => 'Lokasi',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'aktif' => 'Aktif',
            'stok' => 'Stok',
        ];
    }

}
