<?php


namespace backend\modules\document\services\finder;


use common\exceptions\ServiceUnavailable;
use common\services\CurlService;
use common\services\PHPQueryParser;
use backend\modules\document\models\Document;
use backend\modules\document\models\UploadWebDocumentForm;

class GoogleScholarFinder implements IDocumentFinder
{
    const SEARCH_QUERY = 'https://scholar.google.com.ua/scholar?hl=ru&as_ylo=<year_down>&as_yhi=<year_up>&q=<query>&start=<start>';
    const ITEMS_ON_PAGE = 10;

    protected static $filtersLink = ['pdf', 'all'];

    protected static $tags = [
        ['name' => 'name',   'tag' => 'h3',            'attr' => null  ],
        ['name' => 'link',   'tag' => '.gs_or_ggsm a', 'attr' => 'href'],
    ];

    protected static $mainItem = '.gs_r, .gs_or, .gs_scl';

    public function findDocuments(UploadWebDocumentForm $form, int $limit = 20): array
    {
        $theme = urlencode($form->theme);
        $query = self::SEARCH_QUERY;
        $parseItems = [];

        for ($i = 0; $i < $limit; $i += self::ITEMS_ON_PAGE) {
            $query = self::SEARCH_QUERY;
            $query = str_replace('<query>', $theme, $query);
            $query = str_replace('<year_down>', '2015', $query);
            $query = str_replace('<year_up>', '2020', $query);
            $query = str_replace('<start>', (string)$i, $query);

            $page = (new CurlService())->getPageText($query);
            $parser = new PHPQueryParser();
            $item = $parser->parseByItems($page, self::$mainItem, self::$tags);

            if ($item === null) {
                throw new ServiceUnavailable('Google Scholar is unavailable');
            }
            $parseItems = array_merge($parseItems, $item);
        }

        $docs = $this->convertToDocuments($parseItems);
        $docs = $this->filterDocuments($docs);

        return $docs;
    }

    public function convertToDocuments(array $parseItems): array
    {
        $docs = [];
        foreach ($parseItems as $parseItem) {
            $doc = new Document();
            $doc->document_name = $parseItem['name'];
            $doc->doc_source = $parseItem['link'];
            $docs[] = $doc;
        }

        return $docs;
    }

    public function filterDocuments(array $docs): array
    {
        if (!in_array('all', self::$filtersLink)) {
            $filterDoc = [];
            foreach ($docs as $doc) {
                if (strlen($doc->doc_source) > 0 AND strlen($doc->document_name) > 0) {
                    if (self::$filtersLink !== null) {
                        foreach (self::$filtersLink as $filterLink) {
                            if (strripos($doc->doc_source, $filterLink) !== false) {
                                $filterDoc[] = $doc;
                                continue;
                            }
                        }
                    } else {
                        $filterDoc[] = $doc;
                    }
                }
            }

            return $filterDoc;
        } else {
            return $docs;
        }
    }
}