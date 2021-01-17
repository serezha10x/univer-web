<?php

use yii\db\Migration;

/**
 * Class m210102_132225_sub_section
 */
class m210102_132225_sub_section extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('sub_section', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'child_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-sub_section_parent_id-section_id',
            'sub_section',
            'parent_id',
            'section',
            'id',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-sub_section_child_id-section_id',
            'sub_section',
            'child_id',
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
        $this->dropForeignKey(
            'fk-sub_section_parent_id-section_id',
            'sub_section'
        );

        $this->dropForeignKey(
            'fk-sub_section_child_id-section_id',
            'sub_section'
        );

        $this->dropTable('sub_section');
    }
}
