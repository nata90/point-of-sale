<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file_stok_barang".
 *
 * @property int $id
 * @property string $kd_barang
 * @property string $tgl_ed
 * @property float $stok_akhir
 * @property string $nomor_batch
 */
class FileStokBarang extends \yii\db\ActiveRecord
{
    public $nama_barang;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_stok_barang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_barang', 'tgl_ed', 'stok_akhir'], 'required'],
            [['tgl_ed','nama_barang'], 'safe'],
            [['stok_akhir'], 'number'],
            [['kd_barang', 'nomor_batch'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'kd_barang' => Yii::t('app', 'Kode Barang'),
            'tgl_ed' => Yii::t('app', 'Tgl Ed'),
            'stok_akhir' => Yii::t('app', 'Stok Akhir'),
            'nomor_batch' => Yii::t('app', 'Nomor Batch'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FileStokBarangQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileStokBarangQuery(get_called_class());
    }

    public static function detailStok($kd_barang){
        $model = FileStokBarang::find()->where(['kd_barang'=>$kd_barang])->asArray()->all();

        return $model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(FileBarang::className(), ['kd_barang' => 'kd_barang']);
    }
}
