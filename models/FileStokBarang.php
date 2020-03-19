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
            [['tgl_ed'], 'safe'],
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
            'kd_barang' => Yii::t('app', 'Kd Barang'),
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
}
