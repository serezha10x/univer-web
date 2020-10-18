<?php


namespace frontend\modules\document\services\reader;

class PdfReader implements IReader
{
    public function read(string $filename, string $filepath): string
    {
        $parser = new \Smalot\PdfParser\Parser();
        try {
            $pdf = $parser->parseFile($filepath . '/' . $filename);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return '';
        }
        var_dump($pdf->getText()); exit();
        return $pdf->getText();
    }
}
