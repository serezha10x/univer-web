<?php


namespace backend\modules\document\services\vsm;


use common\exceptions\MathException;
use common\helpers\CommonHelper;
use common\helpers\VectorHelper;
use backend\modules\document\models\Document;
use backend\modules\section\models\Section;
use backend\modules\section\service\TensorHandler;
use yii\base\ErrorException;


class VsmSimilar
{
    const GET_GENERAL_WORDS_BY_SECTIONS = 0;
    const GET_GENERAL_WORDS_BY_DOCUMENTS = 1;

    private $document;
    private $section;
    private $getGeneralWordsType = self::GET_GENERAL_WORDS_BY_SECTIONS;

    public function __construct(Document $document, Section $section, $getGeneralWordsType = null)
    {
        $this->document = $document;
        $this->section = $section;
        if ($getGeneralWordsType !== null) {
            $this->getGeneralWordsType = $getGeneralWordsType;
        }
    }

    public function cosineSimilar(): float
    {
        try {
            $words = null;
            if ($this->getGeneralWordsType === self::GET_GENERAL_WORDS_BY_SECTIONS) {
                $words = $this->getGeneralVsmBySections();
            } else if ($this->getGeneralWordsType === self::GET_GENERAL_WORDS_BY_DOCUMENTS) {
                $words = $this->getGeneralVsmByDocuments();
            } else {
                throw new \Exception();
            }
            if (empty($words)) {
                return 0;
            }

            $vector1 = [];
            $vector2 = [];
            VectorHelper::convertVsmToVector($words, $vector1, $vector2);

            $len = VectorHelper::scalarLengthVectors($vector1, $vector2);

            if ($len === 0.0) {
                return 0;
            }
            return VectorHelper::multiplyVectors($vector1, $vector2) / $len;
        } catch (ErrorException $ex) {
            die($ex->getMessage());
        } catch (MathException $ex) {
            return 0;
        }
    }

    public function cosineSoftSimilar(): float
    {
        try {
            $tensorHandler = new TensorHandler($this->section, $this->document->getVsm());

            $words = null;
            if ($this->getGeneralWordsType === self::GET_GENERAL_WORDS_BY_SECTIONS) {
                $words = $this->getGeneralVsmBySections();
            } else if ($this->getGeneralWordsType === self::GET_GENERAL_WORDS_BY_DOCUMENTS) {
                $words = $this->getGeneralVsmByDocuments();
            } else {
                throw new \Exception();
            }

            if (empty($words)) {
                return 0;
            }

            $vector1 = [];
            $vector2 = [];
            VectorHelper::convertVsmToVector($words, $vector1, $vector2);

            $len = VectorHelper::scalarLengthVectors($vector1, $vector2, $tensorHandler->getAdditiveConvolutionCube());

            if ($len === 0.0) {
                return 0;
            }

            return VectorHelper::multiplyVectors($vector1, $vector2, $tensorHandler->getAdditiveConvolutionCube()) / $len;
        } catch (ErrorException $ex) {
            die($ex->getMessage());
        } catch (MathException $ex) {
            return 0;
        }
    }

    private function getGeneralVsmBySections(): array
    {
        $docVsm = $this->document->getVsm();
        $sectionVsm = $this->section->getVsm();
        $generalWords = [];

        foreach ($sectionVsm as $sectionWord => $sectionFreq) {
            if (key_exists($sectionWord, $docVsm)) {
                $generalWords[] = ['word' => $sectionWord, 'docFreq' => $docVsm[$sectionWord], 'sectionFreq' => $sectionVsm[$sectionWord]];
            } else {
                $generalWords[] = ['word' => $sectionWord, 'docFreq' => 0, 'sectionFreq' => $sectionVsm[$sectionWord]];
            }
        }

        return $generalWords;
    }

    private function getGeneralVsmByDocuments(): array
    {
        $docVsm = $this->document->getVsm();
        $sectionVsm = $this->section->getVsm();
        $generalWords = [];

        foreach ($docVsm as $docWord => $docFreq) {
//            var_dump($docWord);
            if (key_exists($docWord, $sectionVsm)) {
                $generalWords[] = ['word' => $docWord, 'docFreq' => $docVsm[$docWord], 'sectionFreq' => $sectionVsm[$docWord]];
            } else {
                $generalWords[] = ['word' => $docWord, 'docFreq' => $docVsm[$docWord], 'sectionFreq' => 0];
            }
        }

        return $generalWords;
    }
}