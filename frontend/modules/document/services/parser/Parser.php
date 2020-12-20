<?php

namespace frontend\modules\document\services\parser;

use frontend\modules\document\models\Property;
use frontend\modules\document\models\Document;

class Parser
{
    private $parsers;
    private $text;

    /**
     * Parser constructor.
     * @param $text
     * @param $parsers
     */
    public function __construct($text, $parsers = null)
    {
        $this->text = $text;
        $this->parsers = $parsers;
    }

    /**
     * @param string $parser
     * @return $this
     */
    public function addParser(string $parser)
    {
        if (in_array($parser, $this->getParsers())) {
            $this->parsers[] = $parser;
        }
        return $this;
    }

    /**
     * @param Document $document
     * @return array
     * @throws \yii\db\Exception
     */
    public function parse(Document $document)
    {
        $result = [];
        foreach ($this->parsers as $parser) {
            $parser_answer = (new $parser($this->text))->parse();
            $document->addDocumentProperty(
                Property::getIdByProperty($this->getParser($parser)),
                $parser_answer
            );
            $result[] = $parser_answer;
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getParsers(): array
    {
        return [
            Property::KEY_WORDS => ParserFrequency::class,
            Property::FIO => ParserFio::class,
            Property::EMAIL => ParserEmails::class,
            Property::DATES => ParserDates::class,
            Property::TEACHER => ParserTeachers::class,
        ];
    }

    /**
     * @param string $class
     * @return false|int|string
     */
    private function getParser(string $class)
    {
        return array_search($class, $this->getParsers());
    }
}