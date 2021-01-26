<?php


namespace common\helpers;


class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function toString($arr, $delimiter)
    {
        $text = '';
        foreach ($arr as $item) {
            $text .= ($item . $delimiter);
        }

        return rtrim($text, $delimiter);
    }
}