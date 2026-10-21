<?php

use yii\db\Migration;

/**
 * Class m261016_000000_add_diskon_to_hd_transaksi
 * Menambahkan kolom diskon pada tabel hd_transaksi untuk diskon penjualan.
 */
class m261016_000000_add_diskon_to_hd_transaksi extends Migration
{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('hd_transaksi');
        if (!isset($table->columns['diskon'])) {
            $this->addColumn('hd_transaksi', 'diskon', $this->integer()->defaultValue(0));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('hd_transaksi');
        if (isset($table->columns['diskon'])) {
            $this->dropColumn('hd_transaksi', 'diskon');
        }
    }
}
