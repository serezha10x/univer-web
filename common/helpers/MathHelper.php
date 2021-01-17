<?php


namespace common\helpers;


use backend\modules\section\service\TensorHandler;

class MathHelper
{
    public static function multMatrix(array $arr, $coef, $flags = null)
    {
        $result = [];
        foreach ($arr as $key1 => $arr1) {
            foreach ($arr1 as $key2 => $value2) {
                if ($flags === null) {
                    $result[$key1][$key2] = $value2 * $coef;
                } else {
                    $result[$key1][$key2] = $value2 * $coef * $flags[$key2];
                }
            }
        }

        return $result;
    }

    public static function multVector($arr, $coef, $flags = 1)
    {
        $result = [];
        foreach ($arr as $key => $value) {
            $result[$key] = $value * $coef * $flags[$key];
        }

        return $result;
    }

    public static function additiveConvolutionCube(array $tensor)
    {
        $convolution = [];
        $coef = 1 / count(current(current($tensor)));

        foreach ($tensor as $word1 => $arr1) {
//            var_dump($arr1);
            $tensor[$word1] = self::multMatrix($arr1, $coef);
//            var_dump($arr1);die;
            foreach ($arr1 as $word2 => $arr2) {
                $convolution[$word1][$word2] = 0;
                foreach (TensorHandler::RELATIONS as $relation) {
                    $convolution[$word1][$word2] += $tensor[$word1][$word2][$relation];
                }
            }
        }

//        var_dump(1, $convolution);die;
        return $convolution;
    }

    public static function additiveConvolutionMatrix(array $arr, array $vec)
    {
        $convolution = [];
        $koef = 1 / count(current($arr));
        $arr = MathHelper::multMatrix($arr, $koef, $vec);
        foreach ($arr as $word1 => $arr1) {
            $convolution[$word1] = 0;
            foreach ($arr1 as $word2 => $arr2) {
                $convolution[$word1] += $arr[$word2][$word1];
            }
        }

        return $convolution;
    }
}