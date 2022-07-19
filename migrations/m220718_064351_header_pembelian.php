<?php

use yii\db\Migration;

/**
 * Class m220718_064351_header_pembelian
 */
class m220718_064351_header_pembelian extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('header_pembelian', [
            'id_pembelian' => 'pk',
            'id_supplier' => $this->integer()->defaultValue('1')->notNull(),
            'no_faktur' => $this->string(30)->defaultValue('-')->notNull(),
            'tgl_pembelian' => $this->datetime()->notNull(),
            'keterangan' => $this->string(300)->notNull(),
            'total_pembelian' => $this->decimal(19,4)->notNull(),
            'status_delete' => $this->tinyInteger(1)->defaultValue(0)->notNull(),
            'tgl_delete' => $this->datetime(),
          ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220718_064351_header_pembelian cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220718_064351_header_pembelian cannot be reverted.\n";

        return false;
    }
    */
}
