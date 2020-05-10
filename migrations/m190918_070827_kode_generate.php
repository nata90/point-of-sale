<?php

use yii\db\Migration;

/**
 * Class m190918_070827_kode_generate
 */
class m190918_070827_kode_generate extends Migration
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
        echo "m190918_070827_kode_generate cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('kode_generate', [
          'id' => 'pk',
          'nama_transaksi' => $this->string(200)->notNull(),
          'nama_alias' => $this->string(10)->notNull(),
          'urutan' => $this->integer(11)->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('kode_generate');
    }
}
