<?php


namespace frontend\modules\teacher\services;


use frontend\modules\teacher\models\Teacher;

interface DocsFinder
{
    public function getDocs(Teacher $teacher, int $type = null): array;
}