<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pengeluaran".
 *
 * @property int $id
 * @property string $deskripsi
 * @property float $nilai
 */
class Pengeluaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengeluaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deskripsi', 'nilai', 'tanggal'], 'required'],
            [['nilai'], 'number'],
            [['deskripsi'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deskripsi' => 'Deskripsi',
            'nilai' => 'Nilai',
            'tanggal' => 'Tanggal',
        ];
    }
}
