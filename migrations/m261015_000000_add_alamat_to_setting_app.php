<?php

use yii\db\Migration;

/**
 * Class m261015_000000_add_alamat_to_setting_app
 * Menambahkan kolom alamat pada tabel setting_app untuk alamat toko / cetak nota.
 */
class m261015_000000_add_alamat_to_setting_app extends Migration
{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('setting_app');
        if (!isset($table->columns['alamat'])) {
            $this->addColumn('setting_app', 'alamat', $this->text()->defaultValue(null));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('setting_app');
        if (isset($table->columns['alamat'])) {
            $this->dropColumn('setting_app', 'alamat');
        }
    }
}
