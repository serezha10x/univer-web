<?php

use yii\db\Migration;

/**
 * Class m210125_195749_add_column_handlig_type_to_document
 */
class m210125_195749_add_column_handlig_type_to_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document', 'read_type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'read_type');
    }
}
