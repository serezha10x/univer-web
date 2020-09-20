<?php

use yii\db\Migration;

/**
 * Class m200920_140750_document_type
 */
class m200920_140750_document_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('document_type', [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('document_type');
    }
}
