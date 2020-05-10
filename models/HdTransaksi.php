<?php

namespace app\models;

use Yii;
use app\models\DtTransaksi;

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

    public static function getProdukTerlaris($days_ago, $days_now){
        $command = Yii::$app->db->createCommand('SELECT a.`kd_barang`,c.`nama_barang`,SUM(a.qty) AS total FROM `dt_transaksi` AS a 
            LEFT JOIN `hd_transaksi` AS b ON a.`no_transaksi` = b.`no_transaksi`
            LEFT JOIN `file_barang` AS c ON a.`kd_barang` = c.`kd_barang`
            WHERE b.`tgl_bayar` BETWEEN "'.$days_ago.' 00:00:00" AND "'.$days_now.' 23:59:59" AND a.`status_hapus` = 0 GROUP BY a.`kd_barang`,c.`nama_barang` ORDER BY total DESC LIMIT 10');

        $result= $command->queryAll();

        return $result;
    }

    public static function getTotalTransaksi($date){
        $command = Yii::$app->db->createCommand('SELECT SUM(total) AS total FROM `hd_transaksi` WHERE tgl_bayar BETWEEN "'.date('Y-m-d', strtotime($date)).' 00:00:00" AND "'.date('Y-m-d', strtotime($date)).' 23:59:59" AND status_hapus = 0');

        $result= $command->queryOne();

        return $result['total'];
    }

    public static function getListObat($no_transaksi){

        $command = Yii::$app->db->createCommand('SELECT b.`nama_barang` FROM `dt_transaksi` AS a LEFT JOIN `file_barang` AS b ON a.`kd_barang` = b.`kd_barang` WHERE no_transaksi = "'.$no_transaksi.'"');

        $result= $command->queryAll();
        

        $arr_data = array();

        if($result != null){
            foreach($result as $key=>$row){
                $arr_data[] = $row['nama_barang'];
            }
        }

       return implode(', ', $arr_data);
    }
}
