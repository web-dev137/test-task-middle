<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%valute}}`.
 */
class m231107_164815_create_valute_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%valute}}', [
            'char_code' => $this->string()->notNull()->append("PRIMARY KEY"),
            'name_valute' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%valute}}');
    }
}
