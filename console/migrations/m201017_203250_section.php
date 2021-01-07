<?php

use yii\db\Migration;

/**
 * Class m201017_203250_section
 */
class m201017_203250_section extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('section', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'parent_id' => $this->integer()->defaultValue(null),
            'sections' => $this->text(),
        ]);

        $this->addForeignKey(
            'fk-section_parent_id-section_id',
            'section',
            'parent_id',
            'section',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-section_parent_id-section_id', 'section');

        $this->dropTable('section');
    }
}
