<?php

use yii\db\Migration;

/**
 * Class m261126_000000_rename_nama_supllier_to_nama_supplier
 * Memperbaiki typo nama kolom nama_supllier menjadi nama_supplier pada tabel supplier.
 */
class m261126_000000_rename_nama_supllier_to_nama_supplier extends Migration
{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('supplier', true);
        if ($table !== null && isset($table->columns['nama_supllier']) && !isset($table->columns['nama_supplier'])) {
            if (Yii::$app->db->driverName === 'sqlite') {
                $this->execute('ALTER TABLE "supplier" RENAME COLUMN "nama_supllier" TO "nama_supplier"');
            } else {
                $this->renameColumn('supplier', 'nama_supllier', 'nama_supplier');
            }
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('supplier', true);
        if ($table !== null && isset($table->columns['nama_supplier']) && !isset($table->columns['nama_supllier'])) {
            if (Yii::$app->db->driverName === 'sqlite') {
                $this->execute('ALTER TABLE "supplier" RENAME COLUMN "nama_supplier" TO "nama_supllier"');
            } else {
                $this->renameColumn('supplier', 'nama_supplier', 'nama_supllier');
            }
        }
    }
}
