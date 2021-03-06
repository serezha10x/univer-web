<?php

namespace backend\modules\document\services;

use backend\modules\document\models\DocumentTeacher;

class DocumentService
{
    public static function getTeacherByDocTeacher($id)
    {
        $teachers_by_doc = '';
        $teachers = DocumentTeacher::find()->where(['document_id' => $id])->with('teacher')->all();
        foreach ($teachers as $teacher) {
            $teachers_by_doc .= ($teacher->teacher['surname'] . ' '
                . $teacher->teacher['name'] . ' ' . $teacher->teacher['fathername'] . ', <br/>');
        }
        return rtrim($teachers_by_doc, ', ');
    }
}