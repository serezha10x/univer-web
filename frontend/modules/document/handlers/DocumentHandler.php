<?php

namespace frontend\modules\document\handlers;

use common\services\semantic\WikiSemantic;
use common\services\wiki\WikipediaApi;
use common\services\wordnet\WordNetApi;
use frontend\modules\document\models\Document;
use frontend\modules\document\services\parser\Parser;
use frontend\modules\document\services\parser\ParserDates;
use frontend\modules\document\services\parser\ParserEmails;
use frontend\modules\document\services\parser\ParserFio;
use frontend\modules\document\services\parser\ParserFrequency;
use frontend\modules\document\services\parser\ParserTeachers;

class DocumentHandler
{
    private $document;

    /**
     * @var array
     */
    private $handlers = [
        ParserFrequency::class,
        ParserFio::class,
        ParserEmails::class,
        ParserDates::class,
        ParserTeachers::class
    ];

    /**
     * DocumentHandler constructor.
     * @param Document $document
     * @param array $handlers
     */
    public function __construct(Document $document, array $handlers = null)
    {
        $this->document = $document;
        if ($handlers !== null) {
            $this->handlers = $handlers;
        }
    }

    public function handle()
    {
//        $text = $this->document->read($this->document->file_name_after);
//        $text = mb_convert_encoding($text, "UTF-8");
//        $parser = new Parser($text, $this->handlers);
//
//        $result = $parser->parse($this->document);
        $this->getSemanticWords('PHP');
    }

    private function getSemanticWords(string $word)
    {
        $wiki = new WordNetApi();
        var_dump($wiki->getSynsets('')); exit();
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param array $handlers
     */
    public function setHandlers(array $handlers): void
    {
        $this->handlers = $handlers;
    }
}