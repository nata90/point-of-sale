<?php

namespace app\models;

use Yii;

class Penjualan
{
    public function getTotalPenjualan($start_date, $end_date    ){
        $sql = 'SELECT COALESCE(SUM(total_harga),0) FROM dt_transaksi dt
        left join hd_transaksi ht on dt.no_transaksi = ht.no_transaksi 
        WHERE dt.status_hapus = 0 AND ht.tgl_bayar BETWEEN :start_date AND :end_date AND ht.status_hapus = 0';
        $params = [
            ':start_date' => $start_date. ' 00:00:00',
            ':end_date' => $end_date. ' 23:59:59'
        ];
        return Yii::$app->db->createCommand($sql, $params)->queryScalar();
    }

    public function getTotalTransaksi($start_date, $end_date){
        $sql = 'SELECT COALESCE(COUNT(no_transaksi),0) FROM hd_transaksi ht
        WHERE ht.tgl_bayar BETWEEN :start_date AND :end_date AND ht.status_hapus = 0';
        $params = [
            ':start_date' => $start_date. ' 00:00:00',
            ':end_date' => $end_date. ' 23:59:59'
        ];
        return Yii::$app->db->createCommand($sql, $params)->queryScalar();
    }

    public function getTotalItemTerjual($start_date, $end_date){
        $sql = 'SELECT COALESCE(SUM(qty),0) FROM dt_transaksi dt
        LEFT JOIN hd_transaksi ht on dt.no_transaksi = ht.no_transaksi 
        WHERE dt.status_hapus = 0 AND ht.tgl_bayar BETWEEN :start_date AND :end_date AND ht.status_hapus = 0';
        $params = [
            ':start_date' => $start_date. ' 00:00:00',
            ':end_date' => $end_date. ' 23:59:59'
        ];
        return Yii::$app->db->createCommand($sql, $params)->queryScalar();
    }

    public function getTransaksiTerbaru($limit){
        $sql = 'SELECT 
        ht.no_transaksi,
        ht.tgl_bayar AS waktu, 
        ht.total, "Tunai" AS metode, 
        "Admin" AS kasir,
        (SELECT COALESCE(SUM(dt.qty),0) FROM dt_transaksi dt WHERE dt.no_transaksi = ht.no_transaksi) AS items
        FROM hd_transaksi ht
        WHERE ht.status_hapus = 0 ORDER BY tgl_bayar DESC LIMIT :limit';

        $params = [
            ':limit' => $limit
        ];
        return Yii::$app->db->createCommand($sql, $params)->queryAll();
    }
}