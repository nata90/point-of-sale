<?php

use yii\db\Migration;

/**
 * Class m190806_062251_hd_transaksi
 */
class m190806_062251_hd_transaksi extends Migration
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
        echo "m190806_062251_hd_transaksi cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('hd_transaksi', [
          'id' => 'pk',
          'no_transaksi' => $this->string(64)->notNull(),
          'tgl_bayar' => $this->datetime()->notNull(),
          'status_bayar' => $this->tinyInteger(1)->defaultValue(0),
          'total' => $this->integer(),
          'jumlah_bayar' => $this->integer(),
          'status_hapus' => $this->tinyInteger(1)->defaultValue(0),
          'tgl_hapus' => $this->datetime()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('hd_transaksi');
    }
    
}
