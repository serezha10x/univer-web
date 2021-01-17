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
            'title' => 'Количество отоюранных слов при частотном анализе',
            'key' => 'WORDS_FREQ_ANALYSIS',
            'value' => '5'
        ]);

        $this->insert('settings', [
            'title' => 'Использование мягкого косинусного сходства при множественной загрузки файлов',
            'key' => 'SOFT_COSINE_SIMILARITY',
            'value' => 0
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
