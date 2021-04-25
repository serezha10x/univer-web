<?php


namespace backend\modules\document\services\vsm;


use backend\modules\document\models\Property;
use backend\modules\settings\models\Settings;
use common\exceptions\MathException;
use common\helpers\VectorHelper;
use yii\base\ErrorException;

class ContextSimilarity extends VsmSimilarity
{

    public function getSimilarity()
    {
        try {

            $words = $this->parser->getResultParser(Property::KEY_WORDS)->getArrFreq();
            $words = array_filter($words, function ($item) {
                if ($item > (float) Settings::getSettings('LIMIT_CONTEXT')) {
                    return true;
                }
                return false;
            });
            $this->getGeneralWordsType = self::GET_GENERAL_WORDS_BY_DOCUMENTS;
            $words = array_filter($this->getGeneralVsm($words), function ($item) {
                if ($item['sectionFreq'] > 0) {
                    return true;
                }
                return false;
            });

            if (count($words) === 0) {
                return 0;
            }

            return VectorHelper::getGeoAvg($words);
        } catch (ErrorException $ex) {
            die($ex->getMessage());
        } catch (MathException $ex) {
            return 0;
        }
    }

    public static function getMethodAlias(): string
    {
        return self::CONTEXT_TYPE;
    }
}