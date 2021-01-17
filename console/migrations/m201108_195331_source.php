<?php

use yii\db\Migration;

/**
 * Class m201108_195331_source
 */
class m201108_195331_source extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('source', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'uri' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('source');
    }
}
