<?php

namespace frontend\modules\document\services\reader;

use Yii;

class FileVerification
{
    static function CheckFormat($file_extension) : bool {
        $permit_format = Yii::$app->getModule('document')->params['allowFormats'];
        return in_array($file_extension, $permit_format);
    }
}
