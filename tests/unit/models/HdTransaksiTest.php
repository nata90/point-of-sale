<?php

namespace tests\unit\models;

use app\models\DtTransaksi;
use app\models\DtTransaksiSearch;
use app\models\FileBarang;
use app\models\HdTransaksi;
use app\models\HdTransaksiSearch;
use tests\unit\BaseTestCase;

class HdTransaksiTest extends BaseTestCase
{
    private function seedSalesData()
    {
        (new FileBarang([
            'kd_barang' => 'BRG001',
            'nama_barang' => 'Paracetamol',
            'harga_beli' => 3000,
            'harga_jual' => 5000,
            'stok' => 100,
        ]))->save();

        (new FileBarang([
            'kd_barang' => 'BRG002',
            'nama_barang' => 'Vitamin C',
            'harga_beli' => 6000,
            'harga_jual' => 10000,
            'stok' => 100,
        ]))->save();

        // Active transaction 1 on 2026-11-26
        (new HdTransaksi([
            'no_transaksi' => '202611261',
            'tgl_bayar' => '2026-11-26 09:30:00',
            'status_bayar' => 1,
            'total' => 23000,
            'jumlah_bayar' => 25000,
            'diskon' => 2000,
            'status_hapus' => 0,
        ]))->save();

        (new DtTransaksi([
            'no_transaksi' => '202611261',
            'kd_barang' => 'BRG001',
            'harga_satuan' => 5000,
            'qty' => 3,
            'total_harga' => 15000,
            'status_hapus' => 0,
        ]))->save();

        (new DtTransaksi([
            'no_transaksi' => '202611261',
            'kd_barang' => 'BRG002',
            'harga_satuan' => 10000,
            'qty' => 1,
            'total_harga' => 10000,
            'status_hapus' => 0,
        ]))->save();

        // Active transaction 2 on 2026-11-26
        (new HdTransaksi([
            'no_transaksi' => '202611262',
            'tgl_bayar' => '2026-11-26 14:15:00',
            'status_bayar' => 1,
            'total' => 10000,
            'jumlah_bayar' => 10000,
            'diskon' => 0,
            'status_hapus' => 0,
        ]))->save();

        (new DtTransaksi([
            'no_transaksi' => '202611262',
            'kd_barang' => 'BRG001',
            'harga_satuan' => 5000,
            'qty' => 2,
            'total_harga' => 10000,
            'status_hapus' => 0,
        ]))->save();

        // Deleted transaction on 2026-11-26 (should be excluded from summaries)
        (new HdTransaksi([
            'no_transaksi' => '202611263',
            'tgl_bayar' => '2026-11-26 15:00:00',
            'status_bayar' => 1,
            'total' => 50000,
            'jumlah_bayar' => 50000,
            'diskon' => 5000,
            'status_hapus' => 1,
            'tgl_hapus' => '2026-11-26 15:05:00',
        ]))->save();

        (new DtTransaksi([
            'no_transaksi' => '202611263',
            'kd_barang' => 'BRG002',
            'harga_satuan' => 10000,
            'qty' => 5,
            'total_harga' => 50000,
            'status_hapus' => 1,
        ]))->save();
    }

    public function testValidationRulesAndRelations()
    {
        $hd = new HdTransaksi();
        $this->assertFalse($hd->validate());
        $this->assertArrayHasKey('no_transaksi', $hd->errors);

        $dt = new DtTransaksi();
        $this->assertFalse($dt->validate());
        $this->assertArrayHasKey('no_transaksi', $dt->errors);
        $this->assertArrayHasKey('kd_barang', $dt->errors);

        $this->seedSalesData();

        $header = HdTransaksi::findOne(['no_transaksi' => '202611261']);
        $this->assertCount(2, $header->details);
        $this->assertSame('Paracetamol', $header->details[0]->barang->nama_barang);
        $this->assertSame('202611261', $header->details[0]->header->no_transaksi);
    }

    public function testAggregationsExcludeDeletedTransactions()
    {
        $this->seedSalesData();

        $this->assertEquals(33000, HdTransaksi::getTotalTransaksi('2026-11-26'));
        $this->assertSame(33000.0, HdTransaksi::getTotalRupiahJual('2026-11-26', '2026-11-26'));
        $this->assertSame(2000.0, HdTransaksi::getTotalDiskonJual('2026-11-26', '2026-11-26'));
        $this->assertSame(6.0, HdTransaksi::getTotalItemJual('2026-11-26', '2026-11-26'));

        // Date with no transactions returns 0.0
        $this->assertSame(0.0, HdTransaksi::getTotalRupiahJual('2026-01-01', '2026-01-01'));
        $this->assertSame(0.0, HdTransaksi::getTotalDiskonJual('2026-01-01', '2026-01-01'));
        $this->assertSame(0.0, HdTransaksi::getTotalItemJual('2026-01-01', '2026-01-01'));
    }

    public function testGetProdukTerlarisAndListObat()
    {
        $this->seedSalesData();

        $top = HdTransaksi::getProdukTerlaris('2026-11-26', '2026-11-26');
        $this->assertCount(2, $top);
        $this->assertSame('BRG001', $top[0]['kd_barang']);
        $this->assertEquals(5, $top[0]['total']);
        $this->assertSame('BRG002', $top[1]['kd_barang']);
        $this->assertEquals(1, $top[1]['total']);

        $this->assertSame('Paracetamol, Vitamin C', HdTransaksi::getListObat('202611261'));
        $this->assertSame('', HdTransaksi::getListObat('NON_EXISTENT'));
    }

    public function testSearchModelsAndDtTransaksiGetTotal()
    {
        $this->seedSalesData();

        // HdTransaksiSearch excludes status_hapus = 1
        $hdSearch = new HdTransaksiSearch();
        $hdProvider = $hdSearch->search(['HdTransaksiSearch' => ['tgl_bayar' => '2026-11-26']]);
        $this->assertCount(2, $hdProvider->getModels());

        // DtTransaksiSearch::getTotal multiplies field1 * field2
        $rows = [
            ['harga_satuan' => 5000, 'qty' => 3],
            ['harga_satuan' => 10000, 'qty' => 2],
        ];
        $this->assertSame(35000, DtTransaksiSearch::getTotal($rows, 'harga_satuan', 'qty'));

        // DtTransaksiSearch::search & searchReport filter by date range and set session
        $dtSearch = new DtTransaksiSearch();
        $dtProvider = $dtSearch->search([
            'DtTransaksiSearch' => [
                'start_date' => '2026-11-26',
                'end_date' => '2026-11-26',
            ],
        ]);
        $this->assertCount(3, $dtProvider->getModels());

        $reportProvider = (new DtTransaksiSearch())->searchReport([
            'DtTransaksiSearch' => [
                'start_date' => '2026-11-26',
                'end_date' => '2026-11-26',
                'kd_barang' => 'BRG001',
            ],
        ]);
        $this->assertCount(2, $reportProvider->getModels());
    }
}
