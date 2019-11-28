<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "header_pembelian".
 *
 * @property int $id_pembelian
 * @property string $tgl_pembelian
 * @property string $keterangan
 * @property string $total_pembelian
 * @property int $status_delete
 * @property string $tgl_delete
 *
 * @property DetailPembelian[] $detailPembelians
 */
class HeaderPembelian extends \yii\db\ActiveRecord
{
    public $kd_barang;
    public $nama_barang;
    public $satuan;
    public $jumlah;
    public $harga_beli;
    public $harga_jual;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'header_pembelian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_pembelian', 'keterangan'], 'required'],
            [['tgl_pembelian', 'tgl_delete'], 'safe'],
            [['keterangan'], 'string'],
            [['total_pembelian'], 'number'],
            [['status_delete'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pembelian' => Yii::t('app', 'Id Pembelian'),
            'tgl_pembelian' => Yii::t('app', 'Tgl Pembelian'),
            'keterangan' => Yii::t('app', 'Keterangan'),
            'total_pembelian' => Yii::t('app', 'Total Pembelian'),
            'status_delete' => Yii::t('app', 'Status Delete'),
            'tgl_delete' => Yii::t('app', 'Tgl Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailPembelians()
    {
        return $this->hasMany(DetailPembelian::className(), ['id_pembelian' => 'id_pembelian']);
    }
}
