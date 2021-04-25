<?php

use yii\db\Migration;

/**
 * Class m210307_132922_add_column_method_type_to_document
 */
class m210307_132922_add_column_method_type_to_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document', 'method_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'method_type');
    }
}
