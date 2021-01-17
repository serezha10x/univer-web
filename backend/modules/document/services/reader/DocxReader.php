<?php


namespace backend\modules\document\services\reader;


class DocxReader implements IReader
{
    public function read(string $filename, string $filepath, $pages = 5, $typeReading = self::BEGIN_END_PAGES): string
    {
        $striped_content = '';
        $content = '';
        if ($filepath === null) {
            $zip = zip_open($_SERVER['DOCUMENT_ROOT']. "/documents/" . $filename);
        } else {
            $zip = zip_open($filepath . '/' . $filename);
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
        return $striped_content;
    }
}
