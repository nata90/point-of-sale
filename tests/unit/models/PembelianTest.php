<?php

namespace tests\unit\models;

use app\models\DetailPembelian;
use app\models\FileBarang;
use app\models\HeaderPembelian;
use app\models\HeaderPembelianSearch;
use tests\unit\BaseTestCase;

class PembelianTest extends BaseTestCase
{
    public function testHeaderAndDetailValidation()
    {
        $header = new HeaderPembelian();
        $this->assertFalse($header->validate());
        $this->assertArrayHasKey('tgl_pembelian', $header->errors);

        $detailInvalid = new DetailPembelian([
            'id_pembelian' => 99999,
            'kd_barang' => 'BRG001',
            'jumlah' => 5,
            'harga_beli' => 1000,
            'harga_jual' => 1500,
        ]);
        $this->assertFalse($detailInvalid->validate());
        $this->assertArrayHasKey('id_pembelian', $detailInvalid->errors);
    }

    public function testGetItemPembelianHtmlAndEmptyFallback()
    {
        $barang = new FileBarang([
            'kd_barang' => 'BRG050',
            'nama_barang' => 'Gula Pasir 1kg',
            'harga_beli' => 14000,
            'harga_jual' => 16500,
            'stok' => 20,
        ]);
        $this->assertTrue($barang->save());

        $header = new HeaderPembelian([
            'id_supplier' => 1,
            'no_faktur' => 'INV-001',
            'tgl_pembelian' => '2026-11-26 08:00:00',
            'keterangan' => 'Restok mingguan',
            'total_pembelian' => 140000,
            'status_delete' => 0,
        ]);
        $this->assertTrue($header->save());

        // Before adding details
        $this->assertSame('-', $header->getItemPembelian($header->id_pembelian));

        $detail = new DetailPembelian([
            'id_pembelian' => $header->id_pembelian,
            'kd_barang' => 'BRG050',
            'satuan' => 'kg',
            'jumlah' => 10,
            'harga_beli' => 14000,
            'harga_jual' => 16500,
            'status_delete' => 0,
        ]);
        $this->assertTrue($detail->save());

        $this->assertCount(1, $header->detailPembelians);
        $this->assertEquals($header->id_pembelian, $detail->pembelian->id_pembelian);
        $this->assertSame('Gula Pasir 1kg', $detail->barang->nama_barang);

        $html = $header->getItemPembelian($header->id_pembelian);
        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('Gula Pasir 1kg', $html);
        $this->assertStringContainsString('Rp 14.000,00', $html);
        $this->assertStringContainsString('Rp 16.500,00', $html);
    }

    public function testHeaderPembelianSearchFiltersDeletedAndDateRange()
    {
        (new HeaderPembelian([
            'id_supplier' => 1,
            'no_faktur' => 'INV-A',
            'tgl_pembelian' => '2026-11-25 10:00:00',
            'keterangan' => 'Aktif',
            'total_pembelian' => 50000,
            'status_delete' => 0,
        ]))->save();

        (new HeaderPembelian([
            'id_supplier' => 1,
            'no_faktur' => 'INV-B',
            'tgl_pembelian' => '2026-11-25 12:00:00',
            'keterangan' => 'Dihapus',
            'total_pembelian' => 75000,
            'status_delete' => 1,
        ]))->save();

        $search = new HeaderPembelianSearch();
        $provider = $search->search([
            'HeaderPembelianSearch' => [
                'start_date' => '2026-11-25',
                'end_date' => '2026-11-26',
            ],
        ]);

        $models = $provider->getModels();
        $this->assertCount(1, $models);
        $this->assertSame('INV-A', $models[0]->no_faktur);
    }
}
