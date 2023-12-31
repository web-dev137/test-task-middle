<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%course}}`.
 */
class m231028_090615_create_course_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%course}}', [
            'char_code' => $this->string()->notNull()->append("PRIMARY KEY"),
            'vunit_rate' => $this->float()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%course}}');
    }
}
