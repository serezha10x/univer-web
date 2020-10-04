<?php

use yii\db\Migration;

/**
 * Class m200913_133514_insert_teachers
 */
class m200913_133514_insert_teachers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('teacher', [
            'name' => 'Наталия',
            'surname' => 'Андриевская',
            'fathername' => 'Климовна',
            'position' => 'Ст. пр.',
            'google_scholar' => 'https://scholar.google.com.ua/citations?hl=ru&user=9aZ3OTcAAAAJ',
            'science_index' => 'http://elibrary.ru/author_profile.asp?id=845456'
        ]);

        $this->insert('teacher', [
            'name' => 'Татьяна',
            'surname' => 'Васяева',
            'fathername' => 'Александровна',
            'position' => 'Доцент',
            'google_scholar' => 'https://scholar.google.com.ua/citations?hl=ru&user=ue6WbGAAAAAJ',
            'science_index' => 'http://elibrary.ru/author_profile.asp?id=852371'
        ]);

        $this->insert('teacher', [
            'name' => 'Светлана',
            'surname' => 'Землянская',
            'fathername' => 'Юрьевна',
            'position' => 'Доцент',
            'google_scholar' => 'https://scholar.google.com.ua/citations?user=0bE2ZxkAAAAJ&hl=ru',
            'science_index' => 'http://elibrary.ru/author_profile.asp?id=848421'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       // $this->delete('teacher');
    }
}
