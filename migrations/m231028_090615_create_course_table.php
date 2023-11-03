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
            'id' => $this->primaryKey(),
            'char_code' => $this->string(3),
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
