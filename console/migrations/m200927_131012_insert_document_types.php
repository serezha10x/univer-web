<?php

use yii\db\Migration;

/**
 * Class m200927_131012_insert_document_types
 */
class m200927_131012_insert_document_types extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('document_type', ['type' => 'Статья']);
        $this->insert('document_type', ['type' => 'Методические указания']);
        $this->insert('document_type', ['type' => 'Лабораторная работы']);
        $this->insert('document_type', ['type' => 'Курсовая работы']);
        $this->insert('document_type', ['type' => 'Дипломная работы (бакалавр)']);
        $this->insert('document_type', ['type' => 'Дипломная работы (магистр)']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //$this->delete('document_type');
    }
}
