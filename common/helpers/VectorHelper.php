<?php


namespace common\helpers;


use common\exceptions\MathException;

class VectorHelper
{
    public static function convertVsmToVector(array $vsm, array &$vector1, array &$vector2)
    {
        foreach ($vsm as $item) {
            $vector1[$item['word']] = $item['docFreq'];
            $vector2[$item['word']] = $item['sectionFreq'];
        }
    }

    public static function multiplyVectors(array $vector1, array $vector2, array $softMatrix = null)
    {
        if (count($vector1) !== count($vector2)) {
            throw new MathException('Cannot multiply vectors with different elements count');
        }

        $mult = 0;
        foreach ($vector1 as $word => $value) {
            if ($softMatrix === null) {
                $mult += ($vector1[$word] * $vector2[$word]);
            } else {
                $mult += ($vector1[$word] * $vector2[$word] * $softMatrix[$word][$word]);
            }
        }

        return $mult;
    }

    public static function scalarLengthVectors(array $vector1, array $vector2, $softMatrix = null)
    {
        return VectorHelper::scalarVectorValue($vector1, $softMatrix) * VectorHelper::scalarVectorValue($vector2, $softMatrix);
    }

    public static function scalarVectorValue(array $vector, $softMatrix = null)
    {
        $sum = 0;
        if ($softMatrix === null) {
            foreach ($vector as $point) {
                $sum += pow($point, 2);
            }
        } else {
            foreach ($vector as $word => $point) {
                $sum += (pow($point, 2) * $softMatrix[$word][$word]);
            }
        }
//        if ($sum === 0) {
//            throw new MathException('Division by zero');
//        }

        return sqrt($sum);
    }

    public static function getStringFromVector(array $vsm)
    {
        $result = '';

        foreach ($vsm as $word => $freq) {
            $result .= "$word: $freq<br>";
        }

        return $result;
    }

    public static function getGeoAvg(array $vsm)
    {
        $avg = 1;
        foreach ($vsm as $word) {
            $avg *= $word['sectionFreq'];
        }

        return pow($avg, 1 / count($vsm));
    }
}