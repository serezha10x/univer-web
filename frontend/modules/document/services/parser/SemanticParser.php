<?php

namespace App\Parser;


use App\Semantic\ISemanticParsable;
use App\Semantic\WikiSemantic;
use App\Semantic\WordNetSemantic;


final class SemanticParser extends ParserBase
{
    protected $text;
    protected $term;
    protected $parser;

    private $test_term = 'IT';
    private $test_text =
        'Let us say you want to save precious bandwidth and develop locally. In this case, you will want
        to install a web server, such as Apache, and of course PHP. If you need you can also use Nginx.
        You will most likely want to install a database as well, such as MySQL, PostgreSQL or SQLite after all all these ones use SQL.
        It is easy to setup a web server with PHP support on any operating system, including MacOS, Linux and Windows.
        For developing on Android platform from Google, firstly, you need knowledge of Java or Kotlin.
        And for iOS platform from Apple you probably need to know Swift or more older Objective-C.
        Of course, in addition to the above, you should know the principles of SOLID and some patterns.
        Knowledge of internet protocols such as DHCP, IP, TCP, HTTP, UDP e.t.c will not be superfluous.
    ';


    public function __construct(&$text, string $term, ISemanticParsable $parser)
    {
        parent::__construct($text);
        $this->term = $term;
        $this->parser = $parser;
    }


    public function TestWikiTerms() {
        $parser = new WikiSemantic();
        $time = microtime(TRUE);
        var_dump($this->getWordsByTerm($this->test_text, $this->test_term, $parser));
        echo "Время выполнения: " . (microtime(TRUE) - $time) / 1000000;
    }


    public function TestWordNetTerms() {
        $parser = new WordNetSemantic();
        $time = microtime(TRUE);
        var_dump($this->getWordsByTerm($this->test_text, $this->test_term, $parser));
        echo "Время выполнения: " . (microtime(TRUE) - $time) / 1000000;
    }



    public function getWordsByTerm(string $text, string $term, ISemanticParsable $parser): array
    {
        $all_terms = require base_path() . '/App/Semantic/semantic_config/semantic_terms.php';
        $need_terms = $all_terms[$term];
        $arr_return = array();
        $arr_text = preg_split('@\s@u', preg_replace('@[^A-Za-z\s]@u', '', $this->text));
        foreach ($arr_text as $text_item) {
            if (FALSE === in_array($text_item, $arr_return)) {
                if (strlen($text_item) >= 2) {
                    $parse_terms = $parser->getTermsByWords($text_item);
                    foreach ($need_terms as $term) {
                        if (FALSE !== stripos($parse_terms, $term)) {
                            $arr_return[] = $text_item;
                            break;
                        }
                    }
                }
            }
        }
        return $arr_return;
    }

    public function parse()
    {
        return "Найденные слова из тематики $this->term: ". implode($this->getWordsByTerm($this->text, $this->test_term, $this->parser), ', ');
    }
}
