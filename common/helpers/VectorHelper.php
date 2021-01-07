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

    public static function multiplyVectors(array $vector1, array $vector2)
    {
        if (count($vector1) !== count($vector2)) {
            throw new MathException('Cannot multiply vectors with different elements count');
        }

        $mult = 0;
        foreach ($vector1 as $word => $value) {
            $mult += ($vector1[$word] * $vector2[$word]);
        }

        return $mult;
    }

    public static function scalarLengthVectors(array $vector1, array $vector2)
    {
        return VectorHelper::scalarVectorValue($vector1) * VectorHelper::scalarVectorValue($vector2);
    }

    public static function scalarVectorValue(array $vector)
    {
        $sum = 0;
        foreach ($vector as $point) {
            $sum += pow($point, 2);
        }
        if ($sum === 0) {
            throw new MathException('Division by zero');
        }

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
}