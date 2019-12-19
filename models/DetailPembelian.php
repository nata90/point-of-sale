<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detail_pembelian".
 *
 * @property int $id
 * @property int $id_pembelian
 * @property string $kd_barang
 * @property string $satuan
 * @property string $jumlah
 * @property int $harga_beli
 * @property int $harga_jual
 * @property int $status_delete
 * @property string $tgl_delete
 *
 * @property HeaderPembelian $pembelian
 */
class DetailPembelian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_pembelian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_barang','jumlah','harga_beli', 'harga_jual'], 'required'],
            [['id_pembelian', 'harga_beli', 'harga_jual', 'status_delete'], 'integer'],
            [['jumlah'], 'number'],
            [['tgl_delete'], 'safe'],
            [['kd_barang'], 'string', 'max' => 30],
            [['satuan'], 'string', 'max' => 10],
            [['id_pembelian'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderPembelian::className(), 'targetAttribute' => ['id_pembelian' => 'id_pembelian']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_pembelian' => Yii::t('app', 'Id Pembelian'),
            'kd_barang' => Yii::t('app', 'Kd Barang'),
            'satuan' => Yii::t('app', 'Satuan'),
            'jumlah' => Yii::t('app', 'Jumlah'),
            'harga_beli' => Yii::t('app', 'Harga Beli'),
            'harga_jual' => Yii::t('app', 'Harga Jual'),
            'status_delete' => Yii::t('app', 'Status Delete'),
            'tgl_delete' => Yii::t('app', 'Tgl Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPembelian()
    {
        return $this->hasOne(HeaderPembelian::className(), ['id_pembelian' => 'id_pembelian']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(FileBarang::className(), ['kd_barang' => 'kd_barang']);
    }
}
