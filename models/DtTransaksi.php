<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dt_transaksi".
 *
 * @property int $id
 * @property string $no_transaksi
 * @property string $kd_barang
 * @property int $harga_satuan
 * @property int $qty
 * @property int $total_harga
 * @property int $status_hapus
 * @property string $tgl_hapus
 */
class DtTransaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dt_transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_transaksi', 'kd_barang'], 'required'],
            [['harga_satuan', 'qty', 'total_harga', 'status_hapus'], 'integer'],
            [['tgl_hapus'], 'safe'],
            [['no_transaksi'], 'string', 'max' => 64],
            [['kd_barang'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_transaksi' => 'No Transaksi',
            'kd_barang' => 'Kd Barang',
            'harga_satuan' => 'Harga Satuan',
            'qty' => 'Qty',
            'total_harga' => 'Total Harga',
            'status_hapus' => 'Status Hapus',
            'tgl_hapus' => 'Tgl Hapus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(FileBarang::className(), ['kd_barang' => 'kd_barang']);
    }
}
