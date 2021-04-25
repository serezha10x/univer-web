<?php


namespace backend\modules\document\services\vsm;


use backend\modules\document\models\Document;
use backend\modules\document\services\parser\Parser;
use backend\modules\section\models\Section;

abstract class VsmSimilarity
{
    const GET_GENERAL_WORDS_BY_SECTIONS = 0;
    const GET_GENERAL_WORDS_BY_DOCUMENTS = 1;

    const COSINE_TYPE = 'cosine';
    const SOFT_COSINE_TYPE = 'soft_cosine';
    const CONTEXT_TYPE = 'context';
    const AVG_TYPE = 'avg';

    public static $fieldNames = [
        self::COSINE_TYPE => 'section_id',
        self::SOFT_COSINE_TYPE => 'section_id_soft',
        self::CONTEXT_TYPE => 'section_id_context',
        self::AVG_TYPE => 'section_id_avg',

    ];

    protected $document;
    protected $section;
    protected $parser;
    protected $getGeneralWordsType = self::GET_GENERAL_WORDS_BY_SECTIONS;

    public function __construct(Document $document, Section $section, Parser $parser = null, $getGeneralWordsType = null)
    {
        $this->document = $document;
        $this->section = $section;
        $this->parser = $parser;
        if ($getGeneralWordsType !== null) {
            $this->getGeneralWordsType = $getGeneralWordsType;
        }
    }
    
    abstract public function getSimilarity();

    abstract static public function getMethodAlias(): string;

    public static function getMethodTypes()
    {
        return [
            self::COSINE_TYPE => 'Косинус',
            self::SOFT_COSINE_TYPE => 'Мягкий косинус',
            self::CONTEXT_TYPE => 'Контекстный',
            self::AVG_TYPE => 'Среднее взвешенное',
        ];
    }

    public static function getFieldName($methodType)
    {
        return self::$fieldNames[$methodType];
    }

    protected function getGeneralVsm($docVsm = null): array
    {
        if ($docVsm === null) {
            $docVsm = $this->document->getVsm();
        }
        $sectionVsm = $this->section->getVsm();
        $generalWords = [];
            
        if ($this->getGeneralWordsType === self::GET_GENERAL_WORDS_BY_SECTIONS) {
            foreach ($sectionVsm as $sectionWord => $sectionFreq) {
                if (key_exists($sectionWord, $docVsm)) {
                    $generalWords[] = ['word' => $sectionWord, 'docFreq' => $docVsm[$sectionWord], 'sectionFreq' => $sectionVsm[$sectionWord]];
                } else {
                    $generalWords[] = ['word' => $sectionWord, 'docFreq' => 0, 'sectionFreq' => $sectionVsm[$sectionWord]];
                }
            }
        } else if ($this->getGeneralWordsType === self::GET_GENERAL_WORDS_BY_DOCUMENTS) {
            foreach ($docVsm as $docWord => $docFreq) {
                if (key_exists($docWord, $sectionVsm)) {
                    $generalWords[] = ['word' => $docWord, 'docFreq' => $docVsm[$docWord], 'sectionFreq' => $sectionVsm[$docWord]];
                } else {
                    $generalWords[] = ['word' => $docWord, 'docFreq' => $docVsm[$docWord], 'sectionFreq' => 0];
                }
            }
        }

        return $generalWords;
    }
}