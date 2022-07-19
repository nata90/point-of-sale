<?php

use yii\db\Migration;

/**
 * Class m220718_065135_detail_pembelian
 */
class m220718_065135_detail_pembelian extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('detail_pembelian', [
            'id' => 'pk',
            'id_pembelian' => $this->integer()->notNull(),
            'kd_barang' => $this->string(30)->notNull(),
            'satuan' => $this->string(10)->defaultValue('-')->notNull(),
            'jumlah' => $this->decimal(19,4)->defaultValue(0)->notNull(),
            'harga_beli' => $this->integer()->defaultValue(0)->notNull(),
            'harga_jual' => $this->integer()->defaultValue(0)->notNull(),
            'status_delete' => $this->tinyInteger(1)->defaultValue(0)->notNull(),
            'tgl_delete' => $this->datetime(),
          ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220718_065135_detail_pembelian cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220718_065135_detail_pembelian cannot be reverted.\n";

        return false;
    }
    */
}
