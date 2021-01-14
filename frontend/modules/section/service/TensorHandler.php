<?php


namespace frontend\modules\section\service;


use common\helpers\CommonHelper;
use common\helpers\MathHelper;
use frontend\modules\document\models\Document;
use frontend\modules\document\services\vsm\VsmSimilar;
use frontend\modules\section\models\Section;

class TensorHandler
{
    const RELATIONS = [
        'RELATION_IS_A' => 0,
        'RELATION_HAS_A' => 1,
        'RELATION_ASSOC' => 2,
    ];

    private $section;
    private $query;

    /**
     * TensorHandler constructor.
     * @param $section
     * @param $query
     */
    public function __construct($section, $query)
    {
        $this->section = $section;
        $this->query = $query;
//        $this->query = CommonHelper::getKeywordsFromQuery($query);
//        $this->query = $this->getQueryVsm();
    }


    public function getVsm()
    {
        /* тензор по ключевым словам */
        $thematicTensor = $this->getThematicTensor();
//                var_dump($thematicTensor);die;
        /* свертка тензора по ключевым словам */
        $convolutionCube = MathHelper::additiveConvolutionCube($thematicTensor);
        /* свертка матрицы по поисковому запросу */
        $convolutionMatrix = MathHelper::additiveConvolutionMatrix($convolutionCube, $this->query);

        return $convolutionMatrix;
    }

    public function getAdditiveConvolutionCube()
    {
        $thematicTensor = $this->getThematicTensor();
        return MathHelper::additiveConvolutionCube($thematicTensor);
    }

    public function getThematicTensor()
    {
        $thematicTensor = [];
        $keywords = $this->section->getVsm();

        foreach ($keywords as $word1 => $freq1) {
            foreach ($keywords as $word2 => $freq2) {
                foreach (self::RELATIONS as $relation) {
                    $doc = new Document();
                    $doc->setVsm([$word2 => $freq2]);
                    $vsmSimilar = new VsmSimilar($doc, $this->section);
                    $thematicTensor[$word1][$word2][$relation] = $vsmSimilar->cosineSimilar();
                }
            }
        }

//        var_dump($thematicTensor);die;
        return $thematicTensor;
    }


    public function getQueryVsm()
    {
        $queryVsm = [];
        $sectionVsm = $this->section->getVsm();

        $this->query = array_map('mb_strtoupper', $this->query);
        foreach ($sectionVsm as $sectionWord => $sectionFreq) {
            if (in_array(mb_strtoupper($sectionWord), $this->query)) {
                $queryVsm[$sectionWord] = 1;
            } else {
                $queryVsm[$sectionWord] = 0;
            }
        }

        return $queryVsm;
    }
}