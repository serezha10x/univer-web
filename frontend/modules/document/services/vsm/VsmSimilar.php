<?php


namespace frontend\modules\document\services\vsm;


use common\exceptions\MathException;
use common\helpers\CommonHelper;
use common\helpers\VectorHelper;
use frontend\modules\document\models\Document;
use frontend\modules\section\models\Section;
use frontend\modules\section\service\TensorHandler;
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
            $words = $this->getGeneralVsm();
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

            $words = $this->getGeneralVsm();
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

    private function getGeneralVsm(): array
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
}