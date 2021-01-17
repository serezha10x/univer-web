<?php


namespace backend\modules\teacher\services;


use common\services\CurlService;
use common\services\PHPQueryParser;
use backend\modules\document\models\Document;
use backend\modules\teacher\models\Teacher;

class DocsFinderWeb implements DocsFinder
{
    public function getDocs(Teacher $teacher, int $type = null): array
    {
        $curlService = new CurlService();
        $page = $curlService->getPageText($teacher->google_scholar);
        $parser = new PHPQueryParser();
        $docsNames = $parser->ParseArray($page, ['.gsc_a_tr .gsc_a_t a'])[0];
        $documents = [];
        foreach ($docsNames as $doc) {
            $document = new Document();
            $document->document_name = $doc;
            $documents[] = $document;
        }
        return $documents;
    }
}