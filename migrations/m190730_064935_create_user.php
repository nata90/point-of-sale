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
        /*$this->createTable('user', [
          'id' => $this->primaryKey(),
          'username' => $this->string(64)->notNull(),
          'password' => $this->integer()->notNull()->defaultValue(10),
          'description' => $this->text(),
          'rule_name' => $this->string(64),
          'data' => $this->text(),
          'created_at' => $this->datetime()->notNull(),
          'updated_at' => $this->datetime(),
        ]);*/

        $this->createTable('user', [
          'id' => 'pk',
          'username' => $this->string(64)->notNull(),
          'password' => $this->string(100)->notNull(),
          'name' => $this->string(100),
          'id_group' =>$this->integer(11),
          'aktif' => $this->tinyInteger(1)->defaultValue(0),
          'created_at' => $this->datetime()->notNull(),
          'updated_at' => $this->datetime(),
        ]);

    }

    public function down()
    {
        $this->dropTable('user');
    }
    
}
