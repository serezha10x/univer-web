<?php

use yii\db\Migration;

/**
 * Class m200920_140754_document
 */
class m200920_140754_document extends Migration
{
    public function safeUp()
    {
        $this->createTable('document', [
            'id' => $this->primaryKey(),
            'document_name' => $this->string(),
            'description' => $this->text(),
            'document_type_id' => $this->integer(),
            'file_name_before' => $this->string(),
            'file_name_after' => $this->string(),
            'year' => $this->integer(),
        ]);


        $this->addForeignKey(
            'fk-document-document_type_id',
            'document',
            'document_type_id',
            'document_type',
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
            'fk-document-document_type_id',
            'document'
        );

        $this->dropTable('document');
    }
}
