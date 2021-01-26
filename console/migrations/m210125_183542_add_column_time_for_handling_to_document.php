<?php

use yii\db\Migration;

/**
 * Class m210125_183542_add_column_time_for_handling_to_document
 */
class m210125_183542_add_column_time_for_handling_to_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document', 'tth', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'tth');
    }
}
