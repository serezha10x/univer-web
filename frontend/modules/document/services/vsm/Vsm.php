<?php

namespace frontend\modules\document\services\vsm;

use frontend\modules\document\models\Document;
use frontend\modules\document\services\parser\ParserFrequency;

class Vsm
{
    private $vsm;

    private $limit;

    public function __construct(int $limit = 5)
    {
        $this->limit = $limit;
    }

    public function formVectorSpaceModel(ParserFrequency $freqData)
    {
        $this->vsm = [];
        foreach ($freqData->getKeyFreqWords() as $word => $freq) {
            $this->vsm[$word] = $this->calcTermFrequency($freq, $freqData->getCount());
        }

        $this->sortByWeight();
        $this->vsm = $this->limit($this->limit);
    }

    private function calcTermFrequency($freq, $count)
    {
        return $freq / $count;
    }

    private function sortByWeight()
    {
        arsort($this->vsm);
    }

    private function limit(int $limit)
    {
        $count = count($this->vsm);
        if ($count <= $limit) {
            return $this->vsm;
        } else {
            return array_slice($this->vsm, 0, $limit);
        }
    }

    public function saveVsm(Document $document)
    {
        $document->vsm = json_encode($this->vsm);
        $document->save();
    }
}