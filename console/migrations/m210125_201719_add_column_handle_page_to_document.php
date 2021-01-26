<?php

use yii\db\Migration;

/**
 * Class m210125_201719_add_column_handle_page_to_document
 */
class m210125_201719_add_column_handle_page_to_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document', 'pages', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('document', 'pages');
    }
}
