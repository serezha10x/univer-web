<?php


namespace frontend\modules\document\models;


use yii\base\Model;

class UploadWebDocumentForm extends Model
{
    public $theme;

    public function rules()
    {
        return [
            [['theme'], 'required'],
        ];
    }

    public function search()
    {

    }
}