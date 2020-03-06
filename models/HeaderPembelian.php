<?php

namespace app\models;

use Yii;
use app\components\Utility;

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
    public $nama_supplier;
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
            [['tgl_pembelian'], 'required'],
            [['tgl_pembelian', 'tgl_delete', 'id_supplier','nama_supplier'], 'safe'],
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
            'id_supplier' => Yii::t('app', 'Supplier'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailPembelians()
    {
        return $this->hasMany(DetailPembelian::className(), ['id_pembelian' => 'id_pembelian']);
    }

    public function getItemPembelian($id){
        $model = DetailPembelian::find()->where(['id_pembelian'=>$id])->all();

        if($model != null){
            $return = '
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>No</td>
                            <td>Nama Barang</td>
                            <td>Jumlah</td>
                            <td>Harga Beli</td>
                            <td>Harga Jual</td>
                        </tr>';
            $no = 1;
            foreach($model as $val){
                $return .= '<tr>
                    <td>'.$no.'</td>
                    <td>'.$val->barang->nama_barang.'</td>
                    <td>'.round($val->jumlah,2).'</td>
                    <td>'.Utility::rupiah($val->harga_beli).'</td>
                    <td>'.Utility::rupiah($val->harga_jual).'</td>
                </tr>';
                    $no++;
            }
            $return .= '</table>';
        }else{
            $return = '-';
        }

        return $return;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'id_supplier']);
    }
}
