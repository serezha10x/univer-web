<?php


namespace App\Parser;


use TextAnalysis\Analysis\Keywords\Rake;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;
use StopWordFactory;
use TextAnalysis\Filters;
use TextAnalysis\Exceptions\InvalidParameterSizeException;
use TextAnalysis\Taggers\StanfordNerTagger;
use TextAnalysis\Corpus\NameCorpus;
use phpMorphy;
use phpMorphy_Exception;

require_once(base_path() . "/vendor/autoload.php");



final class ParserTextAnalysis extends ParserBase
{
    private $morphy;
    protected $text;


    public function __construct($text)
    {
        parent::__construct($text);
        try {
            $dir = base_path() . "/vendor/cijic/phpmorphy/libs/phpmorphy/dicts";
            $lang = 'ru_RU';
            $this->morphy = new phpMorphy($dir, $lang);
        } catch(phpMorphy_Exception $e) {
            die('Error occured while creating phpMorphy instance: ' . $e->getMessage());
        }
    }


    public function getNGrams(int $num): array {
        $tokens = tokenize($this->text, \TextAnalysis\Tokenizers\GeneralTokenizer::class);
        $normalizedTokens = normalize_tokens($tokens, function($token) { return strtolower(str_replace('.', '', $token)); });
        $rake = rake($normalizedTokens, $num);
        return $rake->getKeywordScores();
    }


    public function getNGramsWithoutStopWords(int $num): array {
        $tokens = tokenize($this->text, \TextAnalysis\Tokenizers\GeneralTokenizer::class);
        $normalizedTokens = normalize_tokens($tokens, function($token) { return strtolower(str_replace('.', '', $token)); });

        $rake = rake($normalizedTokens, $num);
        $results = $rake->getKeywordScores();

        $stop_words = ['i', 'me', 'my', 'myself', 'we', 'us', 'our', 'ours', 'ourselves', 'you', "you're", "you've", "you'll", "you'd", 'your', 'yours', 'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', "she's", 'her', 'hers', 'herself', 'it', "it's", 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'what', 'which', 'who', 'whom', 'this', 'that', "that'll", 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'also', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', "don't", 'should', "should've", 'now', 'd', 'll', 'm', 'o', 're', 've', 'y', 'ain', 'aren', "aren't", 'couldn', "couldn't", 'didn', "didn't", 'doesn', "doesn't", 'hadn', "hadn't", 'hasn', "hasn't", 'haven', "haven't", 'isn', "isn't", 'ma', 'mightn', "mightn't", 'mustn', "mustn't", 'needn', "needn't", 'shan', "shan't", 'shouldn', "shouldn't", 'wasn', "wasn't", 'weren', "weren't", 'won', "won't", 'wouldn', "wouldn't"];
        foreach ($results as $val_ngram => $freq_ngram) {
            $temp_ngram = preg_split('@ @u', $val_ngram);
            for ($i = 0; $i < $num; $i++) {
                if (in_array($temp_ngram[$i], $stop_words)) {
                    unset($results[$val_ngram]);
                    continue;
                }
            }
        }
        return $results;
    }


    public function getFreq() : array {

        $tokenizer = new \TextAnalysis\Tokenizers\GeneralTokenizer();
        $tokens = $tokenizer->tokenize($this->text);
        $normalizedTokens = normalize_tokens($tokens, 'mb_strtoupper');

        try {
            $freqDist = new \TextAnalysis\Analysis\FreqDist($normalizedTokens);
            $freq = $freqDist->getKeyValuesByFrequency();
            $max_freq = array_splice($freq, 0, 20);
        } catch (InvalidParameterSizeException $e) {
            echo $e->getMessage();
            return [''];
        }

        $arr = array();
        $k = 0;
        foreach ($max_freq as $key => $val) {
            if ($part = $this->morphy->getPartOfSpeech($key)) {
                if (in_array('С', $part)) {
                    $arr[] = $this->morphy->lemmatize($key);
                    $k++;
                }
            }
            if ($k === 10) {
                break;
            }
        }
        return $arr;
    }


    public function parse() {
        return 'Частотный анализ (PHPAnalysis): ' . implode($this->getFreq()[0], ', ');
    }
}
