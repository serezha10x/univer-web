<?php

use yii\db\Migration;

/**
 * Class m200913_080927_teacher_indicator
 */
class m200913_080927_teacher_indicator extends Migration
{
    private $table = 'teacher_indicator';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'num_publication' => $this->integer(),
            'num_citations' => $this->integer(),
            'index_hirsha' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
