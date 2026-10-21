<?php

use yii\db\Migration;

/**
 * Class m261015_000001_add_no_telp_to_setting_app
 * Menambahkan kolom no_telp pada tabel setting_app untuk kontak toko / cetak nota.
 */
class m261015_000001_add_no_telp_to_setting_app extends Migration
{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('setting_app');
        if (!isset($table->columns['no_telp'])) {
            $this->addColumn('setting_app', 'no_telp', $this->string(50)->defaultValue(null));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('setting_app');
        if (isset($table->columns['no_telp'])) {
            $this->dropColumn('setting_app', 'no_telp');
        }
    }
}
