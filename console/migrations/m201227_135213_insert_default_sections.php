<?php

use yii\db\Migration;

/**
 * Class m201227_135213_insert_default_sections
 */
class m201227_135213_insert_default_sections extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('section', ['name' => 'Программирование',
            'sections' => json_encode([
                'PHP' => 0.9,
                'JAVA' => 0.5,
                'C++' => 1,
                'C#' => 0.85,
                'КОМПИЛЯТОР' => 0.76,
                'ПРОГРАММА' => 0.5
            ], JSON_UNESCAPED_UNICODE)
            ]);

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
