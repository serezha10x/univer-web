<?php


namespace App\Articles;


use App\Util\MyCURL;
use App\Util\PHPQueryParser;

class ELibrary implements IArticlesParseble
{
    protected $root_url = 'https://elibrary.ru/author_profile.asp?id=';

    protected $tags = [
        'name' => 'div[style=width:540px; margin:0 20px 10px 20px; border:0; padding:0; text-align: center; font-size: 9pt;] font[color=#F26C4F] b',
        'counts' => 'table[width="520"] tr > td > a',
        'counts_at_elibrary' => 'font > a[title=Полный список публикаций автора на портале elibrary.ru]',
        'table' => 'table[width="580"] > tr > td[align="center"] > font[color="#000000"]'
    ];

    protected $equals = [
        'name' => null,
        'counts' => '1',
        'counts_at_elibrary' => null,
        'table' => [
            'hirsh' => '6'
        ]
    ];


    public function getInfo($id)
    {
        $my_curl = new MyCURL();
        $q_parser = new PHPQueryParser();
        var_dump($my_curl->getPageText($this->root_url . $id));
        return $q_parser->ParseArray($my_curl->getPageText($this->root_url . $id), $this->tags, $this->equals);
        //var_dump();
    }
}
