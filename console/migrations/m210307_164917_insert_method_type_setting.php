<?php

use yii\db\Migration;

/**
 * Class m210307_164917_insert_method_type_setting
 */
class m210307_164917_insert_method_type_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'title' => 'Метод вычисления при сохранении раздела (cosine, soft_cosine, context, avg)',
            'key' => 'METHOD_TYPE_SAVE',
            'value' => 'avg'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
