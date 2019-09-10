<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hd_transaksi".
 *
 * @property int $id
 * @property string $no_transaksi
 * @property string $tgl_bayar
 * @property int $status_bayar
 * @property int $total
 * @property int $jumlah_bayar
 * @property int $status_hapus
 * @property string $tgl_hapus
 */
class HdTransaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hd_transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_transaksi'], 'required'],
            [['tgl_bayar', 'tgl_hapus'], 'safe'],
            [['status_bayar', 'total', 'status_hapus','jumlah_bayar'], 'integer'],
            [['no_transaksi'], 'string', 'max' => 64],
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
            'tgl_bayar' => 'Tgl Bayar',
            'status_bayar' => 'Status Bayar',
            'total' => 'Total',
            'jumlah_bayar' => 'Jumlah Bayar',
            'status_hapus' => 'Status Hapus',
            'tgl_hapus' => 'Tgl Hapus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasMany(DtTransaksi::className(), ['no_transaksi' => 'no_transaksi']);
    }
}
