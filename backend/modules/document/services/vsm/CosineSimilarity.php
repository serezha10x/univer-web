<?php


namespace backend\modules\document\services\vsm;


use backend\modules\settings\Settings;
use common\exceptions\MathException;
use common\helpers\VectorHelper;
use yii\base\ErrorException;

class CosineSimilarity extends VsmSimilarity
{
    public function getSimilarity()
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

    public static function getMethodAlias(): string
    {
        return self::COSINE_TYPE;
    }
}