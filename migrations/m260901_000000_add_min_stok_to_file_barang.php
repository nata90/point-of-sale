<?php

use yii\db\Migration;

/**
 * Class m260901_000000_add_min_stok_to_file_barang
 * Menambahkan kolom min_stok untuk ambang stok minimum (Stok Menipis).
 */
class m260901_000000_add_min_stok_to_file_barang extends Migration
{
    public function safeUp()
    {
        $this->addColumn('file_barang', 'min_stok', $this->integer(11)->defaultValue(5));
    }

    public function safeDown()
    {
        $this->dropColumn('file_barang', 'min_stok');
    }
}
