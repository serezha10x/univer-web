<?php

use yii\db\Migration;

/**
 * Class m201017_204405_property
 */
class m201017_204405_property extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('property', [
            'id' => $this->primaryKey(),
            'property' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('property');
    }
}
