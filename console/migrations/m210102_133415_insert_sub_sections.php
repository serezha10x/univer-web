<?php

use backend\modules\section\models\Section;
use backend\modules\section\models\SubSection;
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
//        $prog = new Section();
//        $prog->name = 'Программирование';
//        $prog->sections = json_encode([
//            'PHP' => 0.9,
//            'JAVA' => 0.5,
//            'C++' => 1,
//            'C#' => 0.85,
//            'КОМПИЛЯТОР' => 0.76,
//            'ПРОГРАММА' => 0.5
//        ], JSON_UNESCAPED_UNICODE);
//        $prog->save();
//
//        $sub_prog = new Section();
//        $sub_prog->name = 'Веб-Программирование';
//        $sub_prog->parent_id = $prog->id;
//        $sub_prog->sections = json_encode([
//            'PHP' => 1,
//            'Java Script' => 1,
//            'JS' => 0.7,
//            'Python' => 0.5,
//            'Laravel' => 0.9,
//            'XML' => 0.6
//        ], JSON_UNESCAPED_UNICODE);
//        $sub_prog->save();
//
//        $sub_prog_2 = new Section();
//        $sub_prog_2->name = 'Backend';
//        $sub_prog_2->parent_id = $sub_prog->id;
//        $sub_prog_2->sections = json_encode([
//            'PHP' => 1,
//            'Django' => 0.6,
//            'Yii2' => 0.9,
//            'Python' => 0.5,
//            'Laravel' => 0.9,
//            'JSON' => 0.6,
//            'Doctrine' => 0.8,
//            'Symphony' => 0.75
//        ], JSON_UNESCAPED_UNICODE);
//        $sub_prog_2->save();
//
//        $this->insert('section', ['name' => 'Сервера',
//            'sections' => json_encode([
//                'APACHE' => 0.8,
//                'NGINX' => 0.9,
//                'СЕРВЕР' => 0.8,
//                'САЙТ' => 0.75
//            ], JSON_UNESCAPED_UNICODE)
//        ]);
//
//        $this->insert('section', ['name' => 'Менеджмент',
//            'sections' => json_encode([
//                'СИСТЕМА' => 0.1,
//                'АНАЛИЗ' => 0.3,
//                'РЫНОК' => 0.5,
//                'МЕНЕДЖМЕНТ' => 0.85,
//                'БИЗНЕС-ПРОЦЕСС' => 0.9,
//                'БИЗНЕС' => 0.9
//            ], JSON_UNESCAPED_UNICODE)
//        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
