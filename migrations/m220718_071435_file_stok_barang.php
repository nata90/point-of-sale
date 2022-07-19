<?php

use yii\db\Migration;

/**
 * Class m220718_071435_file_stok_barang
 */
class m220718_071435_file_stok_barang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('file_stok_barang', [
            'id' => 'pk',
            'kd_barang' => $this->string(30)->notNull(),
            'tgl_ed' => $this->date()->notNull(),
            'stok_akhir' => $this->float(19,4)->notNull(),
            'nomor_batch' => $this->string(30),
          ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220718_071435_file_stok_barang cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220718_071435_file_stok_barang cannot be reverted.\n";

        return false;
    }
    */
}
