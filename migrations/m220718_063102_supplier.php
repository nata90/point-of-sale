<?php

use yii\db\Migration;

/**
 * Class m220718_063102_supplier
 */
class m220718_063102_supplier extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('supplier', [
            'id' => 'pk',
            'nama_supllier' => $this->string(100)->defaultValue('-')->notNull(),
            'alamat_supplier' => $this->string(100)->defaultValue('-')->notNull(),
            'no_telp' => $this->string(100)->notNull(),
            'cp' => $this->string(100)->notNull(),
          ]);

          $this->insert('supplier', [
            'nama_supllier' => '-',
            'alamat_supplier' => '-',
            'no_telp'=> '0',
            'cp'=>'0'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220718_063102_supplier cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220718_063102_supplier cannot be reverted.\n";

        return false;
    }
    */
}
