<?php


namespace backend\modules\document\services\reader;

class PdfReader implements IReader
{
    public function read(string $filename, string $filepath, $pages = 7, $typeReading = self::BEGIN_END_PAGES): string
    {
        $parser = new \Smalot\PdfParser\Parser();
        try {
            $pdf = $parser->parseFile($filepath . '/' . $filename);
            $pdfPages = $pdf->getPages();
            if (count($pdfPages) <= $pages) {
                return $pdf->getText();
            }

            $text = '';
            $pageCount = count($pdfPages);

            switch ($typeReading)
            {
                case self::BEGIN_PAGES:
                    for ($i = 0; $i < $pages; ++$i) {
                        $text .= $pdfPages[$i]->getText();
                    }

                    return $text;
                case self::END_PAGES:
                    for ($i = $pageCount - 1; $i >= $pageCount - $pages; --$i) {
                        $text .= $pdfPages[$i]->getText();
                    }

                    return $text;

                case self::BEGIN_END_PAGES:
                    $middle = ceil($pages / 2);
                    for ($i = 0; $i > $middle; ++$i) {
                        $text .= $pdfPages[$i]->getText();
                    }
                    for ($i = $pageCount - 1; $i >= $pageCount - $middle; --$i) {
                        $text .= $pdfPages[$i]->getText();
                    }

                    return $text;
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
