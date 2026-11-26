<?php

namespace tests\unit\components;

use app\components\Utility;
use app\models\KodeGenerate;
use app\models\UrutanTransaksi;
use tests\unit\BaseTestCase;

class UtilityTest extends BaseTestCase
{
    public function testRupiahFormatting()
    {
        $this->assertSame('Rp 0,00', Utility::rupiah(0));
        $this->assertSame('Rp 15.000,00', Utility::rupiah(15000));
        $this->assertSame('Rp 1.250.500,00', Utility::rupiah(1250500));
        $this->assertSame('Rp -5.000,00', Utility::rupiah(-5000));
    }

    public function testGetTotalTransaksiPenjualanWithTotalKey()
    {
        $items = [
            ['kd_barang' => 'BRG000001', 'total' => 10000],
            ['kd_barang' => 'BRG000002', 'total' => 25000],
        ];

        $result = Utility::getTotalTransaksiPenjualan($items, 5000);

        $this->assertSame([
            'total' => 30000,
            'subtotal' => 35000,
            'diskon' => 5000,
        ], $result);
    }

    public function testGetTotalTransaksiPenjualanWithTotalHargaKey()
    {
        $items = [
            ['kd_barang' => 'BRG000001', 'total_harga' => 12000],
            ['kd_barang' => 'BRG000002', 'total_harga' => 8000],
        ];

        $result = Utility::getTotalTransaksiPenjualan($items);

        $this->assertSame([
            'total' => 20000,
            'subtotal' => 20000,
            'diskon' => 0,
        ], $result);
    }

    public function testGetTotalTransaksiPenjualanClampsDiscount()
    {
        $items = [
            ['total' => 15000],
        ];

        // Discount exceeding subtotal is capped at subtotal
        $overDiscount = Utility::getTotalTransaksiPenjualan($items, 50000);
        $this->assertSame([
            'total' => 0,
            'subtotal' => 15000,
            'diskon' => 15000,
        ], $overDiscount);

        // Negative discount is clamped to 0
        $negativeDiscount = Utility::getTotalTransaksiPenjualan($items, -3000);
        $this->assertSame([
            'total' => 15000,
            'subtotal' => 15000,
            'diskon' => 0,
        ], $negativeDiscount);
    }

    public function testGetTotalTransaksiPenjualanWhenEmpty()
    {
        $this->assertSame([
            'total' => 0,
            'subtotal' => 0,
            'diskon' => 0,
        ], Utility::getTotalTransaksiPenjualan([], 5000));

        $this->assertSame([
            'total' => 0,
            'subtotal' => 0,
            'diskon' => 0,
        ], Utility::getTotalTransaksiPenjualan(null, 5000));
    }

    public function testGenerateKodeBarangReturnsDashWhenMissing()
    {
        $this->assertSame('-', Utility::generateKodeBarang());
    }

    public function testGenerateKodeBarangPadsSixDigits()
    {
        $kode = new KodeGenerate([
            'nama_transaksi' => 'barang',
            'nama_alias' => 'BRG',
            'urutan' => 42,
        ]);
        $this->assertTrue($kode->save());

        $this->assertSame('BRG000042', Utility::generateKodeBarang());
    }

    public function testGetNoTransaksiCreatesAndIncrementsDailySequence()
    {
        $todayPrefix = date('Ymd');

        $first = Utility::getNoTransaksi(1);
        $this->assertSame($todayPrefix . '1', $first);

        $second = Utility::getNoTransaksi(1);
        $this->assertSame($todayPrefix . '2', $second);

        $row = UrutanTransaksi::find()->where(['DATE(tgl_transaksi)' => date('Y-m-d')])->one();
        $this->assertNotNull($row);
        $this->assertEquals(2, $row->urutan);
    }

    public function testGetUserIPPrecedenceAndIpv6Loopback()
    {
        $backup = [
            'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? null,
            'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
        ];

        try {
            $_SERVER['HTTP_CLIENT_IP'] = '192.168.1.10';
            $_SERVER['HTTP_X_FORWARDED_FOR'] = '10.0.0.5';
            $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
            $this->assertSame('192.168.1.10', Utility::getUserIP());

            unset($_SERVER['HTTP_CLIENT_IP']);
            $this->assertSame('10.0.0.5', Utility::getUserIP());

            unset($_SERVER['HTTP_X_FORWARDED_FOR']);
            $_SERVER['REMOTE_ADDR'] = '::1';
            $this->assertSame('127.0.0.1', Utility::getUserIP());
        } finally {
            foreach ($backup as $key => $val) {
                if ($val === null) {
                    unset($_SERVER[$key]);
                } else {
                    $_SERVER[$key] = $val;
                }
            }
        }
    }
}
