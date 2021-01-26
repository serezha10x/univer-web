<?php

use yii\db\Migration;

/**
 * Class m210116_074231_insert_default_settings
 */
class m210116_074231_insert_default_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'title' => 'Количество отобранных слов при частотном анализе',
            'key' => 'WORDS_FREQ_ANALYSIS',
            'value' => '5'
        ]);

        $this->insert('settings', [
            'title' => 'Использование мягкого косинусного сходства при множественной загрузки файлов',
            'key' => 'SOFT_COSINE_SIMILARITY',
            'value' => 0
        ]);

        $this->insert('settings', [
            'title' => 'Обогощение содержимого разделов ключевыми словами из документов',
            'key' => 'ADD_SECTIONS_BY_DOCS',
            'value' => 1
        ]);

        $this->insert('settings', [
            'title' => 'Разрешаемое расхождение количества ключевых слов в разделе',
            'key' => 'DIFF_NUM_OF_SECTIONS',
            'value' => 3
        ]);

        $this->insert('settings', [
            'title' => 'Тип чтения документа (ВЕСЬ ТЕКСТ = 0, НАЧАЛО ТЕКСТА = 1, КОНЕЦ ТЕКСТА = 2,
             РАЗРЕЖЕННЫЙ ТЕКСТ = 3, СЕРЕДИНА ТЕКСТА = 4)',
            'key' => 'READING_TYPE',
            'value' => 0
        ]);

        $this->insert('settings', [
            'title' => 'Количество обрабатываемых страниц',
            'key' => 'MAX_PAGES',
            'value' => \backend\modules\document\services\reader\IReader::DEFAULT_PAGES
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
