<?php

use frontend\modules\document\models\Property;
use yii\db\Migration;

/**
 * Class m201018_091927_insert_properties
 */
class m201018_091927_insert_properties extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('property', ['property' => Property::KEY_WORDS]);
        $this->insert('property', ['property' => Property::FIO]);
        $this->insert('property', ['property' => Property::LITERATURE]);
        $this->insert('property', ['property' => Property::UDK]);
        $this->insert('property', ['property' => Property::EMAIL]);
        $this->insert('property', ['property' => Property::DATES]);
        $this->insert('property', ['property' => Property::TEACHER]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
