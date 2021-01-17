<?php


namespace backend\modules\teacher\models;


use common\services\CurlService;
use common\services\PHPQueryParser;
use backend\modules\teacher\models\IArticlesParseble;

class ELibraryParser implements IArticlesParseble
{
    protected $tags = [
        'name' => 'div[style=width:540px; margin:0 20px 10px 20px; border:0; padding:0; text-align: center; font-size: 9pt;] font[color=#F26C4F] b',
        //'counts' => 'table[width="520"] tr > td > a',
        'counts_at_elibrary' => 'font > a[title=Полный список публикаций автора на портале elibrary.ru]',
        'hirsh_index' => 'table[width="580"] > tr > td[align="center"] > font[color="#000000"]',
        'num_citations' => 'table[width="580"] > tr > td[align="center"] > font[color="#000000"]'
    ];

    protected $equals = [
        'name' => null,
        //'counts' => '1',
        'counts_at_elibrary' => null,
        'hirsh_index' => 6,
        'num_citations' => 3
    ];


    public function getInfo(string $url)
    {
        $my_curl = new CurlService();
        $q_parser = new PHPQueryParser();
        //echo ($my_curl->getPageText($url));
        return $q_parser->ParseArray($my_curl->getPageText($url), $this->tags, $this->equals);
        //var_dump();
    }
}
