<?php

use backend\modules\section\models\Section;
use yii\db\Migration;

/**
 * Class m210119_182315_insert_sections
 */
class m210119_182315_insert_sections extends Migration
{
    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function safeUp()
    {
        try {
            foreach (\common\helpers\DonntuSections::getDonntuSections() as $sectionName => $sections) {
                $section = new Section();
                $section->name = $sectionName;
                $section->setSection($sections);
                $section->save();
            }
        } catch (JsonException $ex) {
            echo $ex->getMessage();
            exit();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
