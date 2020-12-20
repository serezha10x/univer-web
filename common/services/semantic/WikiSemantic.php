<?php

namespace common\services\semantic;

use common\services\PHPQueryParser;
use common\services\wiki\WikipediaApi;

class WikiSemantic implements ISemanticParsable
{
    const TERMS_TAG = 'div.mw-parser-output ol li';

    private $parser;
    private $wikiApi;


    public function __construct()
    {
        $this->parser  = new PHPQueryParser();
        $this->wikiApi = new WikipediaApi();
    }


    public function getTermsByWords(string $word): string
    {
        return $this->parser->ParseText($this->wikiApi->GetWikiPage($word), self::TERMS_TAG);
    }
}