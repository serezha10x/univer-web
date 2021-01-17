<?php

namespace backend\modules\document\services\vsm;

use backend\modules\document\services\parser\ParserFrequency;
use backend\modules\settings\models\Settings;

class Vsm
{
    private $vsm;

    private $limit = 5;

    public function __construct()
    {
        $settedLimit = (int)Settings::getSettings('WORDS_FREQ_ANALYSIS');
        if ($settedLimit !== null) {
            $this->limit = $settedLimit;
        }
    }

    public function formVectorSpaceModel(ParserFrequency $freqData)
    {
        $this->vsm = [];
        foreach ($freqData->getKeyFreqWords() as $word => $freq) {
            $this->vsm[$word] = $this->calcTermFrequency($freq, $freqData->getCount());
        }

        $this->sortByWeight();
        $this->limit();

        return json_encode($this->vsm);
    }

    private function calcTermFrequency($freq, $count)
    {
        return $freq / $count;
    }

    private function sortByWeight()
    {
        arsort($this->vsm);
    }

    private function limit()
    {
        $count = count($this->vsm);
        if ($count <= $this->limit) {
            return $this->vsm;
        } else {
            $this->vsm = array_slice($this->vsm, 0, $this->limit);
        }
    }
}