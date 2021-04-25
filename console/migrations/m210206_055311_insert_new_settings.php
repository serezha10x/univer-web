<?php

use yii\db\Migration;

/**
 * Class m210206_055311_insert_new_settings
 */
class m210206_055311_insert_new_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'title' => 'Путь к сохраненным документам',
            'key' => 'DOC_PATH',
            'value' => Yii::getAlias('@app') . '/../documents/'
        ]);

        $this->insert('settings', [
            'title' => 'Коэффициент взвешивания КОСИНУС',
            'key' => 'WEIGHT_KOEF_COSINE',
            'value' => 1 / 3
        ]);

        $this->insert('settings', [
            'title' => 'Коэффициент взвешивания МЯГК. КОСИНУС',
            'key' => 'WEIGHT_KOEF_SOFT_COSINE',
            'value' => 1 / 3
        ]);

        $this->insert('settings', [
            'title' => 'Коэффициент взвешивания КОНТЕКСТ',
            'key' => 'WEIGHT_KOEF_CONTEXT',
            'value' => 1 / 3
        ]);

        $this->insert('settings', [
            'title' => 'Порогове значение для контекстного метода',
            'key' => 'LIMIT_CONTEXT',
            'value' => 0.001
        ]);

        $this->insert('settings', [
            'title' => 'Вкл/Выкл мягкого косинуса (Не забудьте поменять коэффциенты взвешенного поиска)',
            'key' => 'SOFT_COSINE_ENABLE',
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
