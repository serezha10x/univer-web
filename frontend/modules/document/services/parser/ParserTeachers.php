<?php


namespace frontend\modules\document\services\parser;


use frontend\modules\teacher\models\Teacher;

final class ParserTeachers extends ParserBase
{
    /**
     * ParserDates constructor.
     * @param $text
     */
    public function __construct($text)
    {
        parent::__construct($text);
    }

    public function parse()
    {
        $teachers = Teacher::find()->all();
        $foundTeachers = [];
        foreach ($teachers as $teacher) {
            if (preg_match("@$teacher->surname@u", $this->text, $matches)) {
                $foundTeachers[] = $teacher->surname;
            }
        }

        return $foundTeachers;
    }
}