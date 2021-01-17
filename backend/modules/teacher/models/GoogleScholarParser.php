<?php

namespace backend\modules\teacher\models;


use common\services\CurlService;
use common\services\PHPQueryParser;

class GoogleScholarParser implements IArticlesParseble
{
    protected $tags = [
        'name' => '#gsc_prf_in',
        'counts' => '#gsc_rsb_st tbody tr .gsc_rsb_std',
        'titles' => '#gsc_rsb_st tbody tr td .gsc_rsb_f'
    ];


    public function getInfo(string $url)
    {
        $my_curl = new CurlService();
        $q_parser = new PHPQueryParser();
        return $q_parser->ParseArray($my_curl->getPageText($url), $this->tags);
    }
}
