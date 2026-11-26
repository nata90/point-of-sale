<?php

namespace tests\unit\models;

use app\models\FileBarang;
use app\models\FileBarangSearch;
use app\models\FileStokBarang;
use tests\unit\BaseTestCase;

class FileBarangTest extends BaseTestCase
{
    public function testValidationRequiredCustomMessages()
    {
        $model = new FileBarang();
        $this->assertFalse($model->validate());

        $this->assertSame('Kode Barang wajib diisi', $model->getFirstError('kd_barang'));
        $this->assertSame('Nama Barang wajib diisi', $model->getFirstError('nama_barang'));
        $this->assertSame('Harga Beli wajib diisi', $model->getFirstError('harga_beli'));
        $this->assertSame('Harga Jual wajib diisi', $model->getFirstError('harga_jual'));
    }

    public function testValidationUniqueKodeBarang()
    {
        $first = new FileBarang([
            'kd_barang' => 'BRG000001',
            'nama_barang' => 'Indomie Goreng',
            'harga_beli' => 2500,
            'harga_jual' => 3500,
            'stok' => 50,
            'min_stok' => 10,
            'aktif' => 1,
        ]);
        $this->assertTrue($first->save());

        $duplicate = new FileBarang([
            'kd_barang' => 'BRG000001',
            'nama_barang' => 'Indomie Kuah',
            'harga_beli' => 2500,
            'harga_jual' => 3500,
        ]);
        $this->assertFalse($duplicate->validate());
        $this->assertSame('Kode Barang sudah dipakai', $duplicate->getFirstError('kd_barang'));
    }

    public function testDetailStokRelationFiltersFutureExpiryAndSortsAscending()
    {
        $barang = new FileBarang([
            'kd_barang' => 'BRG000010',
            'nama_barang' => 'Susu UHT',
            'harga_beli' => 4500,
            'harga_jual' => 6000,
            'stok' => 30,
        ]);
        $this->assertTrue($barang->save());

        $expired = new FileStokBarang([
            'kd_barang' => 'BRG000010',
            'tgl_ed' => date('Y-m-d', strtotime('-5 days')),
            'stok_akhir' => 5,
            'nomor_batch' => 'B-OLD',
        ]);
        $later = new FileStokBarang([
            'kd_barang' => 'BRG000010',
            'tgl_ed' => date('Y-m-d', strtotime('+60 days')),
            'stok_akhir' => 15,
            'nomor_batch' => 'B-LATER',
        ]);
        $sooner = new FileStokBarang([
            'kd_barang' => 'BRG000010',
            'tgl_ed' => date('Y-m-d', strtotime('+15 days')),
            'stok_akhir' => 10,
            'nomor_batch' => 'B-SOONER',
        ]);

        $this->assertTrue($expired->save());
        $this->assertTrue($later->save());
        $this->assertTrue($sooner->save());

        $batches = $barang->detailStok;
        $this->assertCount(2, $batches);
        $this->assertSame('B-SOONER', $batches[0]->nomor_batch);
        $this->assertSame('B-LATER', $batches[1]->nomor_batch);

        // FileStokBarang::detailStok returns all batches as array
        $allBatches = FileStokBarang::detailStok('BRG000010');
        $this->assertCount(3, $allBatches);

        // Inverse relation getBarang()
        $this->assertSame('Susu UHT', $sooner->barang->nama_barang);
    }

    public function testFileBarangSearchQueries()
    {
        (new FileBarang([
            'kd_barang' => 'BRG000101',
            'nama_barang' => 'Kopi Hitam',
            'lokasi' => 'Rak A',
            'harga_beli' => 1000,
            'harga_jual' => 2000,
            'stok' => 20,
            'aktif' => 1,
        ]))->save();

        (new FileBarang([
            'kd_barang' => 'BRG000102',
            'nama_barang' => 'Teh Celup',
            'lokasi' => 'Rak B',
            'harga_beli' => 1500,
            'harga_jual' => 2500,
            'stok' => 0,
            'aktif' => 1,
        ]))->save();

        // Standard search filter by nama_barang
        $search = new FileBarangSearch();
        $dp = $search->search(['FileBarangSearch' => ['nama_barang' => 'Kopi']]);
        $this->assertCount(1, $dp->getModels());
        $this->assertSame('BRG000101', $dp->getModels()[0]->kd_barang);

        // searchStokKosong only returns stok <= 0
        $emptySearch = new FileBarangSearch();
        $emptyDp = $emptySearch->searchStokKosong([]);
        $this->assertCount(1, $emptyDp->getModels());
        $this->assertSame('BRG000102', $emptyDp->getModels()[0]->kd_barang);

        // Seed batches for ED and before-ED searches
        (new FileStokBarang([
            'kd_barang' => 'BRG000101',
            'tgl_ed' => date('Y-m-d', strtotime('-10 days')),
            'stok_akhir' => 3,
            'nomor_batch' => 'ED-1',
        ]))->save();
        (new FileStokBarang([
            'kd_barang' => 'BRG000101',
            'tgl_ed' => '1970-01-01',
            'stok_akhir' => 5,
            'nomor_batch' => 'NO-ED',
        ]))->save();
        (new FileStokBarang([
            'kd_barang' => 'BRG000101',
            'tgl_ed' => date('Y-m-d', strtotime('+10 days')),
            'stok_akhir' => 4,
            'nomor_batch' => 'SOON-1',
        ]))->save();
        (new FileStokBarang([
            'kd_barang' => 'BRG000101',
            'tgl_ed' => date('Y-m-d', strtotime('+90 days')),
            'stok_akhir' => 8,
            'nomor_batch' => 'SAFE-1',
        ]))->save();

        $edDp = (new FileBarangSearch())->searchBarangED([]);
        $this->assertCount(1, $edDp->getModels());
        $this->assertSame('ED-1', $edDp->getModels()[0]->nomor_batch);

        $beforeEdDp = (new FileBarangSearch())->searchBeforeED([]);
        $this->assertCount(1, $beforeEdDp->getModels());
        $this->assertSame('SOON-1', $beforeEdDp->getModels()[0]->nomor_batch);
    }
}
