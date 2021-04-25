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
        $this->addColumn('document_section', 'method_chosen', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document_section', 'method_chosen');
    }
}
