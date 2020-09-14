<?php

use yii\db\Migration;

/**
 * Class m200913_081318_add_foreign_keys_to_teacher
 */
class m200913_081318_add_foreign_keys_to_teacher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-teacher_indicator-google_scholar_id',
            'teacher',
            'google_scholar_id',
            'teacher_indicator',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-teacher_indicator-science_index_id',
            'teacher',
            'science_index_id',
            'teacher_indicator',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-teacher_indicator-sciverse_scopus_id',
            'teacher',
            'sciverse_scopus_id',
            'teacher_indicator',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-teacher_indicator-google_scholar_id',
            'teacher'
        );

        $this->dropForeignKey(
            'fk-teacher_indicator-science_index_id',
            'teacher'
        );

        $this->dropForeignKey(
            'fk-teacher_indicator-sciverse_scopus_id',
            'teacher'
        );
    }
}
