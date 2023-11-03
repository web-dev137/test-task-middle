<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%identity}}`.
 */
class m231103_041454_create_identity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%identity}}', [
            'id' => $this->string(36),
            'login' => $this->string(),
            'password' => $this->string(),
            'PRIMARY KEY (`id`)'
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci');

        $this->insert('identity',[
            'id' => '7fc43573-c18d-4978-9580-a0982a5c6f50',
            'login' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('qwerty123'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%identity}}');
    }
}
