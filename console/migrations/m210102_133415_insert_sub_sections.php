<?php

use frontend\modules\section\models\Section;
use frontend\modules\section\models\SubSection;
use yii\db\Migration;

/**
 * Class m210102_133415_insert_sub_sections
 */
class m210102_133415_insert_sub_sections extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $prog = new Section();
        $prog->name = 'Программирование';
        $prog->sections = json_encode([
            'PHP' => 0.9,
            'JAVA' => 0.5,
            'C++' => 1,
            'C#' => 0.85,
            'КОМПИЛЯТОР' => 0.76,
            'ПРОГРАММА' => 0.5
        ], JSON_UNESCAPED_UNICODE);
        $prog->save();

        $sub_prog = new Section();
        $sub_prog->name = 'Веб-Программирование';
        $sub_prog->sections = json_encode([
            'PHP' => 1,
            'Java Script' => 1,
            'JS' => 0.7,
            'Python' => 0.5,
            'Laravel' => 0.9,
            'XML' => 0.6
        ], JSON_UNESCAPED_UNICODE);
        $sub_prog->save();

        $sub_section = new SubSection();
        $sub_section->parent_id = $prog->id;
        $sub_section->child_id = $sub_prog->id;
        $sub_section->save();

        $this->insert('section', ['name' => 'Сервера',
            'sections' => json_encode([
                'APACHE' => 0.8,
                'NGINX' => 0.9,
                'СЕРВЕР' => 0.8,
                'САЙТ' => 0.75
            ], JSON_UNESCAPED_UNICODE)
        ]);

        $this->insert('section', ['name' => 'Менеджмент',
            'sections' => json_encode([
                'СИСТЕМА' => 0.1,
                'АНАЛИЗ' => 0.3,
                'РЫНОК' => 0.5,
                'МЕНЕДЖМЕНТ' => 0.85,
                'БИЗНЕС-ПРОЦЕСС' => 0.9,
                'БИЗНЕС' => 0.9
            ], JSON_UNESCAPED_UNICODE)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
