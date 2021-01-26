<?php

use yii\db\Migration;

/**
 * Class m201017_204525_document_property
 */
class m201017_204525_document_property extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('document_property', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'property_id' => $this->integer(),
            'value' => $this->text()
        ]);

        $this->addForeignKey(
            'fk-document_property-document_id',
            'document_property',
            'document_id',
            'document',
            'id',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-document_property-property_id',
            'document_property',
            'property_id',
            'property',
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
            'fk-document_property-document_id',
            'document_property'
        );

        $this->dropForeignKey(
            'fk-document_property-property_id',
            'document_property'
        );

        $this->dropTable('document_property');
    }
}
