<?php


namespace frontend\modules\document\services\vsm;


use common\helpers\CommonHelper;
use common\helpers\VectorHelper;
use frontend\modules\document\models\Document;
use frontend\modules\section\models\Section;
use yii\base\ErrorException;


class VsmSimilar
{
    private $document;
    private $section;

    public function __construct(Document $document, Section $section)
    {
        $this->document = $document;
        $this->section = $section;
    }

    public function cosineSimilar(): float
    {
        try {
            $words = $this->findGeneralWords();
            if (empty($words)) {
                return 0;
            }

            $vector1 = [];
            $vector2 = [];
            VectorHelper::convertVsmToVector($words, $vector1, $vector2);

            return VectorHelper::multiplyVectors($vector1, $vector2) /
                VectorHelper::scalarLengthVectors($vector1, $vector2);
        } catch (ErrorException $ex) {

        }
    }

    private function findGeneralWords(): array
    {
        $docVsm = $this->document->getVsm();
        $sectionVsm = $this->section->getVsm();
        $generalWords = [];

        foreach ($docVsm as $docWord => $docFreq) {
            if (key_exists($docWord, $sectionVsm)) {
                $generalWords[] = ['word' => $docWord, 'docFreq' => $docFreq, 'sectionFreq' => $sectionVsm[$docWord]];
            }
        }

        return $generalWords;
    }
}