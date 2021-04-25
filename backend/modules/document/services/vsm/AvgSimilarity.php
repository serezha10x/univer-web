<?php


namespace backend\modules\document\services\vsm;


use backend\modules\document\models\DocumentSection;
use backend\modules\settings\models\Settings;

class AvgSimilarity extends VsmSimilarity
{
    public function getSimilarity()
    {
        $methods = [
            'WEIGHT_KOEF_COSINE' => CosineSimilarity::class,
            'WEIGHT_KOEF_SOFT_COSINE' => SoftCosineSimilarity::class,
            'WEIGHT_KOEF_CONTEXT' => ContextSimilarity::class,
        ];

        $avg = 0;
        /* @var VsmSimilarity $methodClass */
        /* @var DocumentSection $documentSections */
        foreach ($methods as $key => $methodClass) {
            $documentSections = DocumentSection::find()->where([
                    'document_id' => $this->document->id,
                    'section_id' => $this->section->id,
                    'method_chosen' => $methodClass::getMethodAlias(),
                ]
            )->one();

            $avg += (float) Settings::getSettings($key) * $documentSections->similarity;
        }

        return $avg;
    }

    public static function getMethodAlias(): string
    {
        return self::AVG_TYPE;
    }
}