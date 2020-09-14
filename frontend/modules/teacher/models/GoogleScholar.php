<?php

namespace frontend\modules\teacher\models;


use frontend\models\util\CurlUtil;
use frontend\models\util\PHPQueryParser;

class GoogleScholar implements IArticlesParseble
{
    protected $tags = [
        'name' => '#gsc_prf_in',
        'counts' => '#gsc_rsb_st tbody tr .gsc_rsb_std',
        'titles' => '#gsc_rsb_st tbody tr td .gsc_rsb_f'
    ];


    public function getInfo(string $url)
    {
        $my_curl = new CurlUtil();
        $q_parser = new PHPQueryParser();
        return $q_parser->ParseArray($my_curl->getPageText($url), $this->tags);
    }
}
