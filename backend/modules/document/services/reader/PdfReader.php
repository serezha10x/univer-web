<?php


namespace backend\modules\document\services\reader;

class PdfReader implements IReader
{
    private $pages = [];

    public function read(string $filename, string $filepath, $pages = self::DEFAULT_PAGES, $typeReading = self::BEGIN_END_PAGES): string
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
                case self::ALL_PAGES:
                    for ($i = 0; $i < $pageCount; ++$i) {
                        $text .= $pdfPages[$i]->getText();
                        $this->pages[] = $i + 1;
                    }

                    return $text;

                case self::BEGIN_PAGES:
                    for ($i = 0; $i < $pages; ++$i) {
                        $text .= $pdfPages[$i]->getText();
                        $this->pages[] = $i + 1;
                    }

                    return $text;
                case self::END_PAGES:
                    $start = $pageCount - $pages;
                    for ($i = $start; $i < $pageCount; $i++) {
                        $text .= $pdfPages[$i]->getText();
                        $this->pages[] = $i + 1;
                    }

                    return $text;

                case self::BEGIN_END_PAGES:
                    $quarter = ceil($pages / 4);
                    for ($i = 0; $i < $quarter; ++$i) {
                        $text .= $pdfPages[$i]->getText();
                        $this->pages[] = $i + 1;
                    }
                    for ($i = $pageCount - $quarter; $i < $pageCount; ++$i) {
                        $text .= $pdfPages[$i]->getText();
                        $this->pages[] = $i + 1;
                    }

                    return $text;

                case self::MIDDLE_PAGES:
                    $start = floor(($pageCount - $pages) / 2);
                    $finish = $start + $pages > $pageCount ? $pageCount : $start + $pages;
                    for ($i = $start; $i < $finish; ++$i) {
                        $text .= $pdfPages[$i]->getText();
                        $this->pages[] = $i + 1;
                    }

                    return $text;
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }
}
