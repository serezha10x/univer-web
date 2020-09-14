<?php

use yii\db\Migration;

/**
 * Class m200913_072622_teacher
 */
class m200913_072622_teacher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('teacher', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'fathername' => $this->string()->notNull(),
            'surname' => $this->string()->notNull(),
            'position' => $this->text()->notNull(),

            'google_scholar' => $this->text(),
            'google_scholar_id' => $this->integer(),

            'science_index' => $this->text(),
            'science_index_id' => $this->integer(),
            'spin_code' => $this->text(),

            'sciverse_scopus' => $this->text(),
            'sciverse_scopus_id' => $this->integer(),
            'scopus_author_id' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('teacher');
    }
}
