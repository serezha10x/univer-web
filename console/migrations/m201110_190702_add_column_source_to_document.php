<?php

use yii\db\Migration;

/**
 * Class m201110_190702_add_column_source_to_document
 */
class m201110_190702_add_column_source_to_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document', 'doc_source', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'doc_source');
    }
}
