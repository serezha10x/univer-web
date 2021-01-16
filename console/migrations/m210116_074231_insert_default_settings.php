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
        $this->insert('settings', ['title' => 'Количество слов при частотном анализе',
            'key' => 'WORDS_FREQ_ANALYSIS', 'value' => '5']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
