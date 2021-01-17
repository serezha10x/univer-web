<?php

use yii\db\Migration;

/**
 * Class m201223_202505_add_vsm_to_document_table
 */
class m201223_202505_add_vsm_to_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document', 'vsm', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'vsm');
    }
}
