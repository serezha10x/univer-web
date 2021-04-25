<?php


namespace backend\modules\document\services\reader;


use backend\modules\settings\models\Settings;

class DocxReader implements IReader
{
    use ReaderByWords;


    public function read(string $filename, string $filepath, $pages = self::DEFAULT_PAGES, $typeReading = self::BEGIN_END_PAGES): string
    {
//        var_dump($_SERVER['DOCUMENT_ROOT'] . "/documents/" . $filename);die;
//        $objReader = \PhpOffice\PhpWord\IOFactory::load('C:\Users\kanat\OneDrive\Desktop\Конференция2016\6_Давыденко.docx', 'Word2007');
//        $content = '';
//        foreach ($objReader->getSections() as $section) {
//            $elements = $section->getElements();
//            foreach ($elements as $element) {
//                if (get_class($element) === 'PhpOffice\PhpWord\Element\TextRun') {
//                    foreach ($element->getElements() as $text) {
//                        $content .= $text->getText();
//                    }
//                } else if (get_class($element) === 'PhpOffice\PhpWord\Element\PageBreak') {
//                    echo(123);
//                    die;
//                }
//            }
//        }
//        echo($content);
//        die;
//
        $striped_content = '';
        $content = '';

        if ($filepath === null) {
            $zip = zip_open("$filepath/$filename");
        } else {
            $zip = zip_open("$filepath/$filename");
        }

        if (!$zip || is_numeric($zip)) {
            throw new \Exception('File cannot be opened');
        }

        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);

        $striped_content = strip_tags($content);

//        return $this->getTextByPages($striped_content, 1, self::BEGIN_END_PAGES);
        return $striped_content;
    }

    private function getTextByPages($text, $pages, $typeReading)
    {
        $wordsCount = $this->getPages($text);

        $pagesInDoc = ceil($wordsCount / self::ONE_PAGE_EQ_WORDS);

        switch ($typeReading) {
            case self::ALL_PAGES:
                return $text;

            case self::BEGIN_PAGES:
                $from = 0;
                $to = $pages * self::ONE_PAGE_EQ_WORDS;
                return $this->readByWords($text, $from, $to);

            case self::END_PAGES:
                return $this->readByWords($text, null, null, $pages);

            case self::BEGIN_END_PAGES:
                $from = ($pagesInDoc - $pages) * self::ONE_PAGE_EQ_WORDS;
                $to = $pagesInDoc * self::ONE_PAGE_EQ_WORDS;
        }
    }
}
