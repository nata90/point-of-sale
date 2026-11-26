<?php

namespace tests\unit\support;

use yii\db\Connection;

/**
 * Creates and resets the SQLite schema matching data/db_pos_2.sqlite for unit tests.
 */
class TestSchema
{
    /** @var bool */
    private static $initialized = false;

    /** @var string[] */
    private static $tables = [
        'detail_pembelian',
        'dt_transaksi',
        'file_barang',
        'file_stok_barang',
        'hd_transaksi',
        'header_pembelian',
        'kode_generate',
        'modal',
        'pengeluaran',
        'setting_app',
        'supplier',
        'urutan_transaksi',
        'user',
        'user_group',
    ];

    public static function init(Connection $db)
    {
        if (self::$initialized) {
            return;
        }

        $statements = [
            'CREATE TABLE IF NOT EXISTS "detail_pembelian" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "id_pembelian" int NOT NULL,
                "kd_barang" varchar(30) NOT NULL,
                "satuan" varchar(10) NOT NULL DEFAULT \'-\',
                "jumlah" decimal(19,4) NOT NULL DEFAULT \'0.0000\',
                "harga_beli" int NOT NULL DEFAULT \'0\',
                "harga_jual" int NOT NULL DEFAULT \'0\',
                "status_delete" tinyint(1) NOT NULL DEFAULT \'0\',
                "tgl_delete" datetime DEFAULT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "dt_transaksi" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "no_transaksi" varchar(64) NOT NULL,
                "kd_barang" varchar(30) NOT NULL,
                "harga_satuan" int DEFAULT NULL,
                "qty" int DEFAULT NULL,
                "total_harga" int DEFAULT NULL,
                "id_stok_barang" int DEFAULT NULL,
                "status_hapus" tinyint(1) DEFAULT \'0\',
                "tgl_hapus" datetime DEFAULT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "file_barang" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "kd_barang" varchar(30) NOT NULL,
                "nama_barang" varchar(200) NOT NULL,
                "lokasi" varchar(100) DEFAULT \'-\',
                "harga_beli" int DEFAULT \'0\',
                "harga_jual" int DEFAULT \'0\',
                "stok" int DEFAULT \'0\',
                "aktif" tinyint(1) DEFAULT \'1\',
                "min_stok" INTEGER DEFAULT 5
            )',
            'CREATE TABLE IF NOT EXISTS "file_stok_barang" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "kd_barang" varchar(30) NOT NULL,
                "tgl_ed" date NOT NULL,
                "stok_akhir" float NOT NULL,
                "nomor_batch" varchar(30) DEFAULT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "hd_transaksi" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "no_transaksi" varchar(60) NOT NULL,
                "tgl_bayar" datetime NOT NULL,
                "status_bayar" tinyint(1) DEFAULT \'0\',
                "total" int DEFAULT NULL,
                "jumlah_bayar" int DEFAULT NULL,
                "status_hapus" tinyint(1) DEFAULT \'0\',
                "tgl_hapus" datetime DEFAULT NULL,
                "diskon" integer DEFAULT 0
            )',
            'CREATE TABLE IF NOT EXISTS "header_pembelian" (
                "id_pembelian" INTEGER PRIMARY KEY AUTOINCREMENT,
                "id_supplier" int NOT NULL DEFAULT \'1\',
                "no_faktur" varchar(30) NOT NULL DEFAULT \'-\',
                "tgl_pembelian" datetime NOT NULL,
                "keterangan" varchar(300) NOT NULL DEFAULT \'\',
                "total_pembelian" decimal(19,4) NOT NULL DEFAULT \'0\',
                "status_delete" tinyint(1) NOT NULL DEFAULT \'0\',
                "tgl_delete" datetime DEFAULT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "kode_generate" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "nama_transaksi" varchar(200) NOT NULL,
                "nama_alias" varchar(10) NOT NULL,
                "urutan" int DEFAULT \'0\'
            )',
            'CREATE TABLE IF NOT EXISTS "modal" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "tanggal" DATE NOT NULL,
                "modal_awal" INTEGER NOT NULL,
                "keterangan" TEXT
            )',
            'CREATE TABLE IF NOT EXISTS "pengeluaran" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "deskripsi" varchar(100) NOT NULL,
                "nilai" decimal(10,0) NOT NULL,
                "tanggal" datetime DEFAULT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "setting_app" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "app_name" varchar(200) NOT NULL,
                "email" varchar(100) NOT NULL,
                "ip_address" varchar(30) DEFAULT NULL,
                "alamat" TEXT DEFAULT NULL,
                "no_telp" VARCHAR(50) DEFAULT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "supplier" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "nama_supplier" varchar(100) NOT NULL DEFAULT \'-\',
                "alamat_supplier" varchar(100) NOT NULL DEFAULT \'-\',
                "no_telp" varchar(100) NOT NULL,
                "cp" varchar(100) NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "urutan_transaksi" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "nama_transaksi" varchar(200) NOT NULL,
                "urutan" int DEFAULT \'0\',
                "tgl_transaksi" datetime NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "user" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT,
                "username" varchar(64) NOT NULL,
                "password" varchar(100) NOT NULL,
                "authkey" varchar(100) NOT NULL,
                "accesstoken" varchar(100) NOT NULL,
                "name" varchar(100) DEFAULT NULL,
                "id_group" int DEFAULT NULL,
                "aktif" tinyint(1) DEFAULT \'0\',
                "created_at" datetime NOT NULL,
                "updated_at" datetime DEFAULT NULL
            )',
            'CREATE TABLE IF NOT EXISTS "user_group" (
                "id_group" INTEGER PRIMARY KEY AUTOINCREMENT,
                "group_name" varchar(100) NOT NULL,
                "aktif" tinyint(1) DEFAULT \'0\',
                "created_at" datetime NOT NULL,
                "updated_at" datetime DEFAULT NULL
            )',
        ];

        foreach ($statements as $sql) {
            $db->createCommand($sql)->execute();
        }

        self::$initialized = true;
    }

    public static function reset(Connection $db)
    {
        self::init($db);
        foreach (self::$tables as $table) {
            $db->createCommand('DELETE FROM "' . $table . '"')->execute();
        }
        $db->createCommand('DELETE FROM sqlite_sequence')->execute();
    }
}
