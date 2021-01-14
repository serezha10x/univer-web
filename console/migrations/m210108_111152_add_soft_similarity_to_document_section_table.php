<?php

use yii\db\Migration;

/**
 * Class m210108_111152_add_soft_similarity_to_document_section_table
 */
class m210108_111152_add_soft_similarity_to_document_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document_section', 'soft_similarity', $this->float());
        $this->addColumn('document_section', 'is_soft_similarity_chosen', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document_section', 'is_soft_similarity_chosen');
        $this->dropColumn('document_section', 'soft_similarity');
    }
}
