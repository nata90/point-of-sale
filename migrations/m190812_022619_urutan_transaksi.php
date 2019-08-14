<?php

use yii\db\Migration;

/**
 * Class m190812_022619_urutan_transaksi
 */
class m190812_022619_urutan_transaksi extends Migration
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
        echo "m190812_022619_urutan_transaksi cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('urutan_transaksi', [
          'id' => 'pk',
          'nama_transaksi' => $this->string(200)->notNull(),
          'urutan' => $this->integer(11)->defaultValue(0),
          'tgl_transaksi' => $this->datetime()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('urutan_transaksi');
    }
}
