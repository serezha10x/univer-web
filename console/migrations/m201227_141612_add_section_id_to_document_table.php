<?php

use yii\db\Migration;

/**
 * Class m201227_141612_add_section_id_to_document_table
 */
class m201227_141612_add_section_id_to_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-document-section_id',
            'document',
            'section_id',
            'section',
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
            'fk-document-section_id',
            'document'
        );
    }
}
