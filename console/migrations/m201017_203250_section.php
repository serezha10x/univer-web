<?php

use yii\db\Migration;

/**
 * Class m201017_203250_section
 */
class m201017_203250_section extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('section', [
            'id' => $this->primaryKey(),
            'section' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('section');
    }
}
