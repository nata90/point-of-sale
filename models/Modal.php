<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modal".
 *
 * @property int $id
 * @property string $tanggal
 * @property int $modal_awal
 * @property string|null $keterangan
 */
class Modal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal', 'modal_awal'], 'required'],
            [['tanggal'], 'safe'],
            [['modal_awal'], 'integer'],
            [['keterangan'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tanggal' => 'Tanggal',
            'modal_awal' => 'Modal Awal',
            'keterangan' => 'Keterangan',
        ];
    }
}
