<?php

use yii\db\Migration;

/**
 * Class m171205_130249_create_table_auth
 */
class m171205_130249_create_table_auth extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */

    public function safeDown()
    {
       $this->dropForeignKey('fk-auth-user_id-user-id','auth');
       $this->dropTable('auth');
    }
}
