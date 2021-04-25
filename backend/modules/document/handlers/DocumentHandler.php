<?php

namespace backend\modules\document\handlers;

use backend\modules\document\models\Document;
use backend\modules\document\models\DocumentSection;
use backend\modules\document\models\DocumentType;
use backend\modules\document\models\Property;
use backend\modules\document\services\parser\Parser;
use backend\modules\document\services\vsm\Vsm;
use backend\modules\document\services\vsm\VsmSimilarity;
use backend\modules\section\models\Section;
use backend\modules\settings\models\Settings;
use common\services\wordnet\WordNetApi;

class DocumentHandler
{
    private $document;

    private $parsers;
    
    private $vsmSimilarities;


    /**
     * DocumentHandler constructor.
     * @param Document $document
     * @param array $vsmSimilarities
     * @param array $parsers
     */
    public function __construct(Document $document, array $vsmSimilarities, array $parsers = null)
    {
        $this->document = $document;
        $this->vsmSimilarities = $vsmSimilarities;
        if ($parsers === null) {
            $this->parsers = $this->getParsersByDocumentType();
        } else {
            $this->parsers = $parsers;
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
        $parser = new Parser($text, $this->parsers);

        $result = $parser->parse($this->document);
        $section = new Vsm();
        $this->document->vsm = $section->formVectorSpaceModel($parser->getResultParser(Property::KEY_WORDS));
        $this->document->save();

        $suitableSections = $this->vsmHandle($parser);

        $this->document->setSection($this->document->getMostSuitableSection());
        $this->document->method_type = (string) Settings::getSettings('METHOD_TYPE_SAVE');
        $this->document->tth = (float)microtime(true) - (float)$startTime;
        $this->document->save();
    }
    
    private function vsmHandle($parser)
    {
        $sections = Section::find()->all();
        $similarSections = [];

        foreach ($this->vsmSimilarities as $vsmSimilarity) {
            foreach ($sections as $section) {
                /* @var $similar VsmSimilarity */
                $similar = new $vsmSimilarity($this->document, $section, $parser);
                $similarity = $similar->getSimilarity();
                $similarSections[$section->name][$similar->getMethodAlias()] = $similarity;
                $this->saveDocumentSections($section->name, $similarity, $similar->getMethodAlias());
            }
        }

        return $similarSections;
    }

    private function saveDocumentSections($sectionName, $value, $type)
    {
//        foreach ($suitableSections as $name => $section) {
//            foreach ($section as $type => $value) {
//                $documentSection = new DocumentSection();
//                $documentSection->document_id = $this->document->id;
//                $documentSection->section_id = Section::getIdByName($name);
//                $documentSection->similarity = $value;
//                $documentSection->method_chosen = $type;
//
//                $documentSection->save();
//            }
//        }

        $documentSection = new DocumentSection();
        $documentSection->document_id = $this->document->id;
        $documentSection->section_id = Section::getIdByName($sectionName);
        $documentSection->similarity = $value;
        $documentSection->method_chosen = $type;

        $documentSection->save();
    }

    /**
     * @return array
     */
    public function getParsers(): array
    {
        return $this->parsers;
    }

    /**
     * @param array $parsers
     */
    public function setParsers(array $parsers): void
    {
        $this->parsers = $parsers;
    }

    private function getSemanticWords(string $word)
    {
        $wiki = new WordNetApi();
//        var_dump($wiki->getSynsets('')); exit();
    }
}