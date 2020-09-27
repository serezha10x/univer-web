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
        $this->insert('document_type', ['type' => 'Тезисы']);
        $this->insert('document_type', ['type' => 'Статья']);
        $this->insert('document_type', ['type' => 'Академическая статья']);
        $this->insert('document_type', ['type' => 'Статья на конференции']);
        $this->insert('document_type', ['type' => 'Статья научно-популярная']);
        $this->insert('document_type', ['type' => 'Обзорная статья']);
        $this->insert('document_type', ['type' => 'Структура документа по частям']);
        $this->insert('document_type', ['type' => 'УДК']);
        $this->insert('document_type', ['type' => 'Контакты']);
        $this->insert('document_type', ['type' => 'Аннотация']);
        $this->insert('document_type', ['type' => 'Автор']);
        $this->insert('document_type', ['type' => 'Ключевое слово']);
        $this->insert('document_type', ['type' => 'Ссылка']);
        $this->insert('document_type', ['type' => 'Параграф']);
        $this->insert('document_type', ['type' => 'Тема документа']);
        $this->insert('document_type', ['type' => 'Заголовок']);
        $this->insert('document_type', ['type' => 'Сборник']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('document_type');
    }
}
