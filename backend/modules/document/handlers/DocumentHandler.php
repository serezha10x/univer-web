<?php

namespace backend\modules\document\handlers;

use backend\modules\document\models\Document;
use backend\modules\document\models\DocumentSection;
use backend\modules\document\models\DocumentType;
use backend\modules\document\models\Property;
use backend\modules\document\services\parser\Parser;
use backend\modules\document\services\reader\IReader;
use backend\modules\document\services\vsm\Vsm;
use backend\modules\section\models\Section;
use backend\modules\settings\models\Settings;
use common\services\wordnet\WordNetApi;

class DocumentHandler
{
    private $document;

    private $handlers;


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
        $text = $this->document->read($this->document->file_name_after,
            (int) Settings::getSettings('MAX_PAGES'), (int) Settings::getSettings('READING_TYPE'));
        $text = mb_convert_encoding($text, "UTF-8");

        $startTime = microtime(true);
        $parser = new Parser($text, $this->handlers);

        $result = $parser->parse($this->document);
        $section = new Vsm();
        $this->document->vsm = $section->formVectorSpaceModel($parser->getResultParser(Property::KEY_WORDS));
        $this->document->save();

        $suitableSections = Section::getSectionsForDocument($this->document);
        foreach ($suitableSections as $name => $section) {
            $documentSection = new DocumentSection();
            $documentSection->document_id = $this->document->id;
            $documentSection->section_id = Section::getIdByName($name);
            $documentSection->similarity = $section['similarity'];
            $documentSection->soft_similarity = $section['soft_similarity'];
            $documentSection->is_soft_similarity_chosen = (bool) Settings::getSettings('SOFT_COSINE_SIMILARITY');

            $documentSection->save();
        }

        $this->document->setSection($this->document->getMostSuitableSection());
        $this->document->tth = (float)microtime(true) - (float)$startTime;
        $this->document->save();
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