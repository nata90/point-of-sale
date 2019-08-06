<?php

use yii\db\Migration;

/**
 * Class m190730_070942_create_usergroup
 */
class m190730_070942_create_usergroup extends Migration
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
        echo "m190730_070942_create_usergroup cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('user_group', [
          'id_group' => 'pk',
          'group_name' => $this->string(100)->notNull(),
          'aktif' => $this->tinyInteger(1)->defaultValue(0),
          'created_at' => $this->datetime()->notNull(),
          'updated_at' => $this->datetime(),
        ]);

    }

    public function down()
    {
        $this->dropTable('user_group');
    }
    
}
