<?php

namespace app\controllers;

use app\components\Utility;
use app\models\HdTransaksi;
use app\models\Modal;
use app\models\Pengeluaran;
use app\models\Transaksi;
use Yii;
use yii\web\Controller;

/**
 * TransaksiController implements the CRUD actions for DtTransaksi model.
 */
class AjaxtransaksiController extends Controller
{
    private $transaksi;

    public function init()
    {
        parent::init();

        $this->transaksi = new Transaksi();
    }

    public function actionGetdatapenjualan(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $tgl = Yii::$app->request->get('tgl_transaksi');

        $start_date = $tgl == null ? date('Y-m-d') : date('Y-m-d', strtotime($tgl));
        $end_date = $tgl == null ? date('Y-m-d') : date('Y-m-d', strtotime($tgl));

        $data = $this->transaksi->getDataPenjualan($start_date, $end_date);

        return $data;
    }

    public function actionGetdatapengeluaran(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $tgl = Yii::$app->request->get('tgl_transaksi');

        $start_date = $tgl == null ? date('Y-m-d') : date('Y-m-d', strtotime($tgl));
        $end_date = $tgl == null ? date('Y-m-d') : date('Y-m-d', strtotime($tgl));

        $data = $this->transaksi->getDataPengeluaran($start_date, $end_date);

        return $data;
    }

    /**
     * Hitung ringkasan (KPI) laporan keuangan untuk tanggal tertentu:
     * modal awal, total penjualan, total pengeluaran, dan keuntungan.
     */
    public function actionHitungkpi(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $tgl = Yii::$app->request->get('tgl_transaksi');

        if (empty($tgl)) {
            $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d', strtotime($tgl));
        }

        $modal_awal = (float) Modal::getModalAwal($date);
        $total_penjualan = (float) HdTransaksi::getTotalRupiahJual($date, $date);
        $pengeluaran = (float) Pengeluaran::getTotalPengeluaran($date, $date);
        $keuntungan = $modal_awal + $total_penjualan - $pengeluaran;

        return [
            'modal_awal'        => Utility::rupiah($modal_awal),
            'total_penjualan'   => Utility::rupiah($total_penjualan),
            'pengeluaran'       => Utility::rupiah($pengeluaran),
            'keuntungan'        => Utility::rupiah($keuntungan),
        ];
    }

    public function actionSearchlaporankeuangan(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $response = [];

        try {
            $tgl_transaksi = Yii::$app->request->get('tgl_transaksi');

            $data_penjualan = $this->transaksi->getDataPenjualan(date('Y-m-d', strtotime($tgl_transaksi)), date('Y-m-d', strtotime($tgl_transaksi)));

            $data_pengeluaran = $this->transaksi->getDataPengeluaran(date('Y-m-d', strtotime($tgl_transaksi)), date('Y-m-d', strtotime($tgl_transaksi)));

            $response = [
                'success'=>1,
                'penjualan'=>$data_penjualan,
                'pengeluaran'=>$data_pengeluaran
            ];
        }catch (\Exception $e) {
            $response = [
                'success'=>0,
                'msg'=>$e->getMessage()
            ];
        }

        return $response;
    }

    private function formatErrors($errors) {
        $errorMessages = '';
        foreach ($errors as $fieldName => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $errorMessages .= '<strong> * ' . strtoupper($error) . '</strong><br/>';
            }
        }
        return $errorMessages;
    }
}