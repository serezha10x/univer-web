<?php

use yii\db\Migration;

/**
 * Class m200920_140759_keyword
 */
class m200920_140759_keyword extends Migration
{
    public function safeUp()
    {
        $this->createTable('keyword', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'key' => $this->string(),
            'value' => $this->string(),
        ]);

        $this->addForeignKey(
            'fk-keyword-document_id',
            'keyword',
            'document_id',
            'document',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-keyword-document_id',
            'keyword'
        );

        $this->dropTable('keyword');
    }
}
