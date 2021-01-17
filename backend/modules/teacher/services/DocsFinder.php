<?php


namespace backend\modules\teacher\services;


use backend\modules\teacher\models\Teacher;

interface DocsFinder
{
    public function getDocs(Teacher $teacher, int $type = null): array;
}