<?php


namespace backend\modules\document\services\vsm;


use backend\modules\section\service\TensorHandler;
use common\exceptions\MathException;
use common\helpers\VectorHelper;
use yii\base\ErrorException;

class SoftCosineSimilarity extends VsmSimilarity
{
    public function getSimilarity()
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
//            var_dump($tensorHandler->getAdditiveConvolutionCube(),  $words, $vector1, $vector2);die;

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

    public static function getMethodAlias(): string
    {
        return self::SOFT_COSINE_TYPE;
    }
}