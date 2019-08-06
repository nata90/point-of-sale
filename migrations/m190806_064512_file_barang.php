<?php

use yii\db\Migration;

/**
 * Class m190806_064512_file_barang
 */
class m190806_064512_file_barang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190806_064512_file_barang cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('file_barang', [
          'id' => 'pk',
          'kd_barang' => $this->string(30)->notNull(),
          'nama_barang' => $this->string(200)->notNull(),
          'harga_beli' => $this->integer(11)->defaultValue(0),
          'harga_jual' => $this->integer(11)->defaultValue(0),
          'aktif' => $this->tinyInteger(1)->defaultValue(1),
        ]);
    }

    public function down()
    {
        $this->dropTable('file_barang');
    }
    
}
