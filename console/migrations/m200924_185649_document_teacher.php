<?php

use yii\db\Migration;

/**
 * Class m200924_185649_document_teacher
 */
class m200924_185649_document_teacher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('document_teacher', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'teacher_id' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-document_teacher-document_id',
            'document_teacher',
            'document_id',
            'document',
            'id',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-document_teacher-teacher_id',
            'document_teacher',
            'teacher_id',
            'teacher',
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
            'fk-document_teacher-document_id',
            'document_teacher'
        );

        $this->dropForeignKey(
            'fk-document_teacher-teacher_id',
            'document_teacher'
        );

        $this->dropTable('document_teacher');
    }
}
