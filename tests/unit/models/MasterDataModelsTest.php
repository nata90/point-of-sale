<?php

namespace tests\unit\models;

use app\models\AppUserGroup;
use app\models\KodeGenerate;
use app\models\SettingApp;
use app\models\Supplier;
use app\models\UrutanTransaksi;
use tests\unit\BaseTestCase;

class MasterDataModelsTest extends BaseTestCase
{
    public function testAppUserGroupValidationAndSave()
    {
        $invalid = new AppUserGroup();
        $this->assertFalse($invalid->validate());
        $this->assertArrayHasKey('group_name', $invalid->errors);
        $this->assertArrayHasKey('created_at', $invalid->errors);

        $group = new AppUserGroup([
            'group_name' => 'Kasir',
            'aktif' => 1,
            'created_at' => '2026-11-26 10:00:00',
        ]);
        $this->assertTrue($group->save());
        $this->assertNotNull($group->id_group);
    }

    public function testSettingAppValidationAndSave()
    {
        $invalid = new SettingApp();
        $this->assertFalse($invalid->validate());
        $this->assertArrayHasKey('app_name', $invalid->errors);
        $this->assertArrayHasKey('email', $invalid->errors);

        $setting = new SettingApp([
            'app_name' => 'Warungku POS',
            'email' => 'toko@warungku.test',
            'ip_address' => '127.0.0.1',
            'alamat' => 'Jl. Merdeka No. 1',
            'no_telp' => '08123456789',
        ]);
        $this->assertTrue($setting->save());
        $found = SettingApp::find()->one();
        $this->assertSame('Warungku POS', $found->app_name);
        $this->assertSame('08123456789', $found->no_telp);
    }

    public function testKodeGenerateValidationAndSave()
    {
        $invalid = new KodeGenerate(['urutan' => 'abc']);
        $this->assertFalse($invalid->validate());
        $this->assertArrayHasKey('nama_transaksi', $invalid->errors);
        $this->assertArrayHasKey('nama_alias', $invalid->errors);
        $this->assertArrayHasKey('urutan', $invalid->errors);

        $kode = new KodeGenerate([
            'nama_transaksi' => 'barang',
            'nama_alias' => 'BRG',
            'urutan' => 10,
        ]);
        $this->assertTrue($kode->save());
        $this->assertEquals(10, KodeGenerate::findOne($kode->id)->urutan);
    }

    public function testUrutanTransaksiValidationAndSave()
    {
        $invalid = new UrutanTransaksi(['urutan' => 'bukan-angka']);
        $this->assertFalse($invalid->validate());
        $this->assertArrayHasKey('nama_transaksi', $invalid->errors);
        $this->assertArrayHasKey('tgl_transaksi', $invalid->errors);
        $this->assertArrayHasKey('urutan', $invalid->errors);

        $urutan = new UrutanTransaksi([
            'nama_transaksi' => 'transaksi penjualan',
            'urutan' => 5,
            'tgl_transaksi' => '2026-11-26 09:00:00',
        ]);
        $this->assertTrue($urutan->save());
        $this->assertEquals(5, UrutanTransaksi::findOne($urutan->id)->urutan);
    }

    public function testSupplierValidationSaveAndSearch()
    {
        $invalid = new Supplier();
        $this->assertFalse($invalid->validate());
        $this->assertArrayHasKey('no_telp', $invalid->errors);
        $this->assertArrayHasKey('cp', $invalid->errors);

        $supplier = new Supplier([
            'nama_supplier' => 'PT Berkah Jaya',
            'alamat_supplier' => 'Jl. Sudirman No. 10',
            'no_telp' => '021-5551234',
            'cp' => 'Budi',
        ]);
        $this->assertTrue($supplier->save());
        $this->assertSame('Contact Person', $supplier->getAttributeLabel('cp'));

        $found = Supplier::findOne($supplier->id);
        $this->assertNotNull($found);
        $this->assertSame('PT Berkah Jaya', $found->nama_supplier);

        $search = new \app\models\SupplierSearch();
        $provider = $search->search(['SupplierSearch' => ['nama_supplier' => 'Berkah']]);
        $this->assertCount(1, $provider->getModels());
        $this->assertSame('PT Berkah Jaya', $provider->getModels()[0]->nama_supplier);
    }
}
