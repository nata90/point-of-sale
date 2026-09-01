<?php

namespace app\models;

use Yii;

class Transaksi
{

    public function getDataPenjualan($start_date, $end_date){
        $sql = 'SELECT 
            dt.no_transaksi AS no_transaksi,
            dt.kd_barang AS kode_barang,
            ht.tgl_bayar AS tgl_transaksi,
            fb.nama_barang,
            dt.harga_satuan,
            dt.qty,
            dt.total_harga AS total
        FROM dt_transaksi dt
        LEFT JOIN file_barang fb ON dt.kd_barang = fb.kd_barang 
        LEFT JOIN hd_transaksi ht ON ht.no_transaksi = dt.no_transaksi
        WHERE ht.tgl_bayar BETWEEN :start_date AND :end_date';

        $params = [
            ':start_date' => $start_date . ' 00:00:00',
            ':end_date' => $end_date . ' 23:59:59',
        ];

        $rows = Yii::$app->db->createCommand($sql, $params)->queryAll();

        foreach ($rows as &$row) {
            if (!empty($row['tgl_transaksi'])) {
                $row['tgl_transaksi'] = date('d-m-Y H:i:s', strtotime($row['tgl_transaksi']));
            }
        }
        unset($row);

        return $rows;
    }

    public function getDataPengeluaran($start_date, $end_date){
        $sql = 'SELECT 
            p.tanggal AS tgl_transaksi,
            p.deskripsi AS keterangan,
            p.nilai AS nominal_pengeluaran
        FROM pengeluaran p
        WHERE p.tanggal BETWEEN :start_date AND :end_date';

        $params = [
            ':start_date' => $start_date. ' 00:00:00',
            ':end_date' => $end_date.' 23:59:59'
        ];
        
        return Yii::$app->db->createCommand($sql, $params)->queryAll();
    }
}
