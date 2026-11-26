<?php

namespace tests\unit\models;

use app\models\DtTransaksi;
use app\models\FileBarang;
use app\models\HdTransaksi;
use app\models\Modal;
use app\models\Pengeluaran;
use app\models\Penjualan;
use app\models\Transaksi;
use tests\unit\BaseTestCase;

class KeuanganModelsTest extends BaseTestCase
{
    public function testModalValidationAndGetModalAwal()
    {
        $invalid = new Modal(['modal_awal' => 'bukan-angka']);
        $this->assertFalse($invalid->validate());
        $this->assertArrayHasKey('tanggal', $invalid->errors);
        $this->assertArrayHasKey('modal_awal', $invalid->errors);

        $modal = new Modal([
            'tanggal' => '2026-11-26',
            'modal_awal' => 500000,
            'keterangan' => 'Modal kasir pagi',
        ]);
        $this->assertTrue($modal->save());

        $this->assertEquals(500000, Modal::getModalAwal('2026-11-26'));
        $this->assertFalse(Modal::getModalAwal('2026-01-01'));
    }

    public function testPengeluaranValidationAndTotalRange()
    {
        $invalid = new Pengeluaran(['nilai' => 'abc']);
        $this->assertFalse($invalid->validate());
        $this->assertArrayHasKey('deskripsi', $invalid->errors);
        $this->assertArrayHasKey('nilai', $invalid->errors);
        $this->assertArrayHasKey('tanggal', $invalid->errors);

        (new Pengeluaran([
            'deskripsi' => 'Beli plastik',
            'nilai' => 15000,
            'tanggal' => '2026-11-26 09:00:00',
        ]))->save();

        (new Pengeluaran([
            'deskripsi' => 'Listrik',
            'nilai' => 50000,
            'tanggal' => '2026-11-26 17:30:00',
        ]))->save();

        (new Pengeluaran([
            'deskripsi' => 'Lain hari',
            'nilai' => 25000,
            'tanggal' => '2026-11-20 10:00:00',
        ]))->save();

        $this->assertEquals(65000, Pengeluaran::getTotalPengeluaran('2026-11-26', '2026-11-26'));
        $this->assertEquals(90000, Pengeluaran::getTotalPengeluaran('2026-11-20', '2026-11-26'));
        $this->assertEquals(0, Pengeluaran::getTotalPengeluaran('2026-01-01', '2026-01-02'));
    }

    public function testPenjualanMetricsAndTransaksiTerbaru()
    {
        (new FileBarang([
            'kd_barang' => 'BRG100',
            'nama_barang' => 'Air Mineral',
            'harga_beli' => 2000,
            'harga_jual' => 3000,
            'stok' => 50,
        ]))->save();

        (new HdTransaksi([
            'no_transaksi' => 'TRX001',
            'tgl_bayar' => '2026-11-26 08:00:00',
            'status_bayar' => 1,
            'total' => 6000,
            'jumlah_bayar' => 10000,
            'diskon' => 0,
            'status_hapus' => 0,
        ]))->save();
        (new DtTransaksi([
            'no_transaksi' => 'TRX001',
            'kd_barang' => 'BRG100',
            'harga_satuan' => 3000,
            'qty' => 2,
            'total_harga' => 6000,
            'status_hapus' => 0,
        ]))->save();

        (new HdTransaksi([
            'no_transaksi' => 'TRX002',
            'tgl_bayar' => '2026-11-26 12:00:00',
            'status_bayar' => 1,
            'total' => 9000,
            'jumlah_bayar' => 10000,
            'diskon' => 0,
            'status_hapus' => 0,
        ]))->save();
        (new DtTransaksi([
            'no_transaksi' => 'TRX002',
            'kd_barang' => 'BRG100',
            'harga_satuan' => 3000,
            'qty' => 3,
            'total_harga' => 9000,
            'status_hapus' => 0,
        ]))->save();

        // Deleted transaction should not be counted
        (new HdTransaksi([
            'no_transaksi' => 'TRX003',
            'tgl_bayar' => '2026-11-26 13:00:00',
            'status_bayar' => 1,
            'total' => 30000,
            'jumlah_bayar' => 30000,
            'diskon' => 0,
            'status_hapus' => 1,
        ]))->save();
        (new DtTransaksi([
            'no_transaksi' => 'TRX003',
            'kd_barang' => 'BRG100',
            'harga_satuan' => 3000,
            'qty' => 10,
            'total_harga' => 30000,
            'status_hapus' => 0,
        ]))->save();

        $penjualan = new Penjualan();
        $this->assertEquals(15000, $penjualan->getTotalPenjualan('2026-11-26', '2026-11-26'));
        $this->assertEquals(2, $penjualan->getTotalTransaksi('2026-11-26', '2026-11-26'));
        $this->assertEquals(5, $penjualan->getTotalItemTerjual('2026-11-26', '2026-11-26'));

        $latest = $penjualan->getTransaksiTerbaru(1);
        $this->assertCount(1, $latest);
        $this->assertSame('TRX002', $latest[0]['no_transaksi']);
        $this->assertEquals(3, $latest[0]['items']);
        $this->assertSame('Tunai', $latest[0]['metode']);
    }

    public function testTransaksiReportQueries()
    {
        (new FileBarang([
            'kd_barang' => 'BRG200',
            'nama_barang' => 'Beras 5kg',
            'harga_beli' => 60000,
            'harga_jual' => 70000,
            'stok' => 10,
        ]))->save();

        (new HdTransaksi([
            'no_transaksi' => 'TRX200',
            'tgl_bayar' => '2026-11-26 10:15:30',
            'status_bayar' => 1,
            'total' => 65000,
            'jumlah_bayar' => 70000,
            'diskon' => 5000,
            'status_hapus' => 0,
        ]))->save();

        (new DtTransaksi([
            'no_transaksi' => 'TRX200',
            'kd_barang' => 'BRG200',
            'harga_satuan' => 70000,
            'qty' => 1,
            'total_harga' => 70000,
            'status_hapus' => 0,
        ]))->save();

        (new Pengeluaran([
            'deskripsi' => 'Ongkir supplier',
            'nilai' => 20000,
            'tanggal' => '2026-11-26 11:00:00',
        ]))->save();

        $transaksi = new Transaksi();
        $salesRows = $transaksi->getDataPenjualan('2026-11-26', '2026-11-26');
        $this->assertCount(1, $salesRows);
        $this->assertSame('TRX200', $salesRows[0]['no_transaksi']);
        $this->assertSame('Beras 5kg', $salesRows[0]['nama_barang']);
        $this->assertSame('26-11-2026 10:15:30', $salesRows[0]['tgl_transaksi']);
        $this->assertEquals(5000, $salesRows[0]['diskon_transaksi']);

        $expenseRows = $transaksi->getDataPengeluaran('2026-11-26', '2026-11-26');
        $this->assertCount(1, $expenseRows);
        $this->assertSame('Ongkir supplier', $expenseRows[0]['keterangan']);
        $this->assertEquals(20000, $expenseRows[0]['nominal_pengeluaran']);
    }
}
