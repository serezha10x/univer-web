<?php

use yii\db\Migration;

/**
 * Class m200920_140804_literature
 */
class m200920_140804_literature extends Migration
{
    public function safeUp()
    {
        $this->createTable('literature', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'author' => $this->string(),
            'name' => $this->string(),
        ]);

        $this->addForeignKey(
            'fk-literature-document_id',
            'literature',
            'document_id',
            'document',
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
            'fk-literature-document_id',
            'literature'
        );

        $this->dropTable('literature');
    }
}
