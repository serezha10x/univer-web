<?php


namespace frontend\modules\document\services\reader;


use Exception;


class ReaderCreator
{
    public static function factory($file_ext): IReader
    {
        try {
            if (isset($filename) && !file_exists($filename)) {
                throw new Exception('File is not exists...');
            }

            if (!FileVerification::CheckFormat($file_ext)) {
                throw new Exception('This format (' . $file_ext . ') is not supported...');
            }

            $reader_class = '@frontend\modules\document\services\reader\\' . ucfirst($file_ext) . 'Reader';
            if (class_exists($reader_class)) {
                return new $reader_class;
            } else {
                throw new Exception("There is no such a file reader...");
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
}
