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

    public function tree($array, $tab = '', $result = '')
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result .= "{$tab}<i class='fa fa-folder'></i><b>   $key</b><br>";
                $result .= $this->tree($value, $tab . str_repeat('&nbsp;', 6));
            } else {
                $result .= "{$tab}<i class='fa fa-file'></i>   $value<br>";
            }
        }
        return $result;
    }
}