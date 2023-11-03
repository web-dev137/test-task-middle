<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m231103_020555_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'identity' => $this->string(36),
            'username' => $this->string()->notNull(),
            'FOREIGN KEY (identity) REFERENCES identity(id) ON UPDATE CASCADE ON DELETE SET NULL'
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
