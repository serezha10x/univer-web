<?php

namespace frontend\modules\document\handlers;

use common\services\wordnet\WordNetApi;
use frontend\modules\document\models\Document;
use frontend\modules\document\models\DocumentSection;
use frontend\modules\document\models\DocumentType;
use frontend\modules\document\models\Property;
use frontend\modules\document\services\parser\Parser;
use frontend\modules\document\services\vsm\Vsm;
use frontend\modules\section\models\Section;

class DocumentHandler
{
    private $document;

    private $handlers;
    /**
     * @var array
     */


    /**
     * DocumentHandler constructor.
     * @param Document $document
     * @param array $handlers
     */
    public function __construct(Document $document, array $handlers = null)
    {
        $this->document = $document;
        if ($handlers === null) {
            $this->handlers = $this->getParsersByDocumentType();
        } else {
            $this->handlers = $handlers;
        }
    }

    public function getParsersByDocumentType()
    {
        $addParsers = [];
        switch ($this->document->document_type_id) {
            case DocumentType::KURSOVOY:
                break;
        }

        return array_merge(Parser::$defaultParsers, $addParsers);
    }

    public function textHandle()
    {
        $text = $this->document->read($this->document->file_name_after);
        $text = mb_convert_encoding($text, "UTF-8");
        $parser = new Parser($text, $this->handlers);

        $result = $parser->parse($this->document);
        $section = new Vsm();
        $section->formVectorSpaceModel($parser->getResultParser(Property::KEY_WORDS));
        $section->saveVsm($this->document);

        $suitableSections = Section::getSectionsForDocument($this->document);
        foreach ($suitableSections as $sectionName => $similarity) {
            $documentSection = new DocumentSection();
            $documentSection->document_id = $this->document->id;
            $documentSection->section_id = Section::getIdByName($sectionName);
            $documentSection->similarity = $similarity;
            $documentSection->save();
        }
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

    private function getSemanticWords(string $word)
    {
        $wiki = new WordNetApi();
//        var_dump($wiki->getSynsets('')); exit();
    }
}