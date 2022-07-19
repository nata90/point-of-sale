<?php

use yii\db\Migration;

/**
 * Class m220718_063550_setting_app
 */
class m220718_063550_setting_app extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('setting_app', [
            'id' => 'pk',
            'app_name' => $this->string(200)->notNull(),
            'email' => $this->string(100)->notNull(),
            'ip_address' => $this->string(30),
          ]);

          $this->insert('setting_app', [
            'app_name' => 'POS System',
            'email' => 'rahanata9@gmail.com',
            'ip_address'=> '127.0.0.1',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220718_063550_setting_app cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220718_063550_setting_app cannot be reverted.\n";

        return false;
    }
    */
}
