<?php

use yii\db\Migration;

/**
 * Class m190730_064935_create_user
 */
class m190730_064935_create_user extends Migration
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
        echo "m190730_064935_create_user cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('user', [
          'id' => 'pk',
          'username' => $this->string(64)->notNull(),
          'password' => $this->string(100)->notNull(),
          'authkey' => $this->string(100)->notNull(),
          'accesstoken' => $this->string(100)->notNull(),
          'name' => $this->string(100),
          'id_group' =>$this->integer(),
          'aktif' => $this->tinyInteger(1)->defaultValue(0),
          'created_at' => $this->datetime()->notNull(),
          'updated_at' => $this->datetime(),
        ]);

        $this->insert('user', [
          'username' => 'admin',
          'password' => '342acf8a1d482e65876ac159bc540a92',
          'authkey'=> 'f7700fd6671892bb24883dd2745933e9',
          'accesstoken'=> '-',
          'name'=> 'Administrator',
          'id_group'=> '1',
          'aktif'=> '1',
          'created_at'=>date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }
    
}
