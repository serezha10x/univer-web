<?php

namespace common\services\wordnet;

use AL\PhpWndb\DiContainerFactory;
use AL\PhpWndb\WordNet;

class WordNetApi
{
    private $wordNet;


    public function __construct()
    {
        $containerFactory = new DiContainerFactory();
        $container = $containerFactory->createContainer();
        $this->wordNet = $container->get(WordNet::class);
    }

    public function getSynsets(string $word) {
        return $this->wordNet->searchSynsets($word)->getAllSynsets();
    }

    public function getSynsetsGloss(string $word) : string {
        $arr_gloss = '';
        $synsets = $this->wordNet->searchSynsets($word)->getAllSynsets();
        foreach ($synsets as $synset) {
            $arr_gloss .= (' ' . $synset->getGloss());
        }
        return $arr_gloss;
    }

    public function getFullInfoSynsets(string $word) {
        $synsets = $this->wordNet->searchSynsets($word)->getAllSynsets();
        foreach ($synsets as $synset) {
            echo $word . '  (' . $synset->getPartOfSpeech() . ') '. '<br>' . $synset->getGloss() . '<br>';
        }
    }
}