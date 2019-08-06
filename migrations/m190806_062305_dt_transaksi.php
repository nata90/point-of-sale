<?php

use yii\db\Migration;

/**
 * Class m190806_062305_dt_transaksi
 */
class m190806_062305_dt_transaksi extends Migration
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
        echo "m190806_062305_dt_transaksi cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
         $this->createTable('dt_transaksi', [
          'id' => 'pk',
          'no_transaksi' => $this->string(64)->notNull(),
          'kd_barang' => $this->string(30)->notNull(),
          'harga_satuan' => $this->integer(11),
          'qty' => $this->integer(10),
          'total_harga' => $this->integer(11),
          'status_hapus' => $this->tinyInteger(1)->defaultValue(0),
          'tgl_hapus' => $this->datetime()->notNull(),
        ]);
    }

    public function down()
    {
       $this->dropTable('dt_transaksi');
    }
    
}
