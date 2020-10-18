<?php

use yii\db\Migration;

/**
 * Class m201017_203517_document_section
 */
class m201017_203517_document_section extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('document_section', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'section_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-document_section-document_id',
            'document_section',
            'document_id',
            'document',
            'id',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-document_section-section_id',
            'document_section',
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
            'fk-document_section-document_id',
            'document_section'
        );

        $this->dropForeignKey(
            'fk-document_section-section_id',
            'document_section'
        );

        $this->dropTable('document_section');
    }
}
