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

    public static function getTotalPengeluaran($start_date, $end_date){
        $sql = 'SELECT COALESCE(SUM(nilai),0) FROM pengeluaran WHERE tanggal BETWEEN :start_date AND :end_date';

        $params = [
            ':start_date' => $start_date. ' 00:00:00',
            ':end_date' => $end_date.' 23:59:59'
        ];
        
        return Yii::$app->db->createCommand($sql, $params)->queryScalar();
    }
}
