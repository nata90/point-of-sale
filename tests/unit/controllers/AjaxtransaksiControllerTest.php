<?php

namespace tests\unit\controllers;

use app\controllers\AjaxtransaksiController;
use app\models\DtTransaksi;
use app\models\FileBarang;
use app\models\HdTransaksi;
use app\models\Modal;
use app\models\Pengeluaran;
use tests\unit\BaseTestCase;
use Yii;

class AjaxtransaksiControllerTest extends BaseTestCase
{
    private function seedDailyData($date = '2026-11-26')
    {
        (new FileBarang([
            'kd_barang' => 'BRG300',
            'nama_barang' => 'Minyak Goreng',
            'harga_beli' => 15000,
            'harga_jual' => 18000,
            'stok' => 40,
        ]))->save();

        (new Modal([
            'tanggal' => $date,
            'modal_awal' => 200000,
            'keterangan' => 'Kas awal',
        ]))->save();

        (new HdTransaksi([
            'no_transaksi' => 'TRX300',
            'tgl_bayar' => $date . ' 10:00:00',
            'status_bayar' => 1,
            'total' => 34000,
            'jumlah_bayar' => 50000,
            'diskon' => 2000,
            'status_hapus' => 0,
        ]))->save();

        (new DtTransaksi([
            'no_transaksi' => 'TRX300',
            'kd_barang' => 'BRG300',
            'harga_satuan' => 18000,
            'qty' => 2,
            'total_harga' => 36000,
            'status_hapus' => 0,
        ]))->save();

        (new Pengeluaran([
            'deskripsi' => 'Es batu',
            'nilai' => 10000,
            'tanggal' => $date . ' 11:00:00',
        ]))->save();
    }

    public function testActionHitungKpiCalculatesFormattedSummary()
    {
        $this->seedDailyData('2026-11-26');
        Yii::$app->request->setQueryParams(['tgl_transaksi' => '2026-11-26']);

        $controller = new AjaxtransaksiController('ajaxtransaksi', Yii::$app);
        $result = $controller->actionHitungkpi();

        // keuntungan = 200000 + 34000 - 10000 = 224000
        $this->assertSame([
            'modal_awal' => 'Rp 200.000,00',
            'total_penjualan' => 'Rp 34.000,00',
            'total_diskon' => 'Rp 2.000,00',
            'pengeluaran' => 'Rp 10.000,00',
            'keuntungan' => 'Rp 224.000,00',
        ], $result);
    }

    public function testActionGetDataPenjualanAndPengeluaran()
    {
        $this->seedDailyData('2026-11-26');
        Yii::$app->request->setQueryParams(['tgl_transaksi' => '2026-11-26']);

        $controller = new AjaxtransaksiController('ajaxtransaksi', Yii::$app);

        $sales = $controller->actionGetdatapenjualan();
        $this->assertCount(1, $sales);
        $this->assertSame('Minyak Goreng', $sales[0]['nama_barang']);

        $expenses = $controller->actionGetdatapengeluaran();
        $this->assertCount(1, $expenses);
        $this->assertSame('Es batu', $expenses[0]['keterangan']);
    }

    public function testActionSearchLaporanKeuanganReturnsCombinedPayload()
    {
        $this->seedDailyData('2026-11-26');
        Yii::$app->request->setQueryParams(['tgl_transaksi' => '2026-11-26']);

        $controller = new AjaxtransaksiController('ajaxtransaksi', Yii::$app);
        $response = $controller->actionSearchlaporankeuangan();

        $this->assertSame(1, $response['success']);
        $this->assertCount(1, $response['penjualan']);
        $this->assertCount(1, $response['pengeluaran']);
    }
}
