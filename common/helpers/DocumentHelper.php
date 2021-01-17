<?php


namespace common\helpers;


class DocumentHelper
{
    public static function getFormat(string $fileSource): string
    {
        $format = substr($fileSource, strrpos($fileSource, '.') + 1, strlen($fileSource));
        if (strpos($format, '#') !== false) {
            $format = substr($format, 0, strrpos($format, '#'));
        }

        return $format;
    }
}