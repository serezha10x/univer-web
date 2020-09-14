<?php


namespace frontend\models\util;

use phpQuery;


class PHPQueryParser
{

    public function ParseText(&$text, string $tags): string
    {
        try {
            $doc = phpQuery::newDocument($text);
            $parse_text = $doc->find($tags)->text();
            phpQuery::unloadDocuments();
            return $parse_text;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }


    public function ParseArray($text, array $tags, array $eq = null): array
    {
        try {
            $parse_arr = [];
            $doc = phpQuery::newDocument($text);
            foreach ($tags as $key => $tag) {
                if ($eq[$key] === null) {
                    $parsed_data = $doc->find($tag);
                } else if (is_array($eq[$key])) {
                    foreach ($eq[$key] as $e_key => $e_val) {
                        $parsed_data[$e_key] = $doc->find($tag)->eq($e_val);
                    }
                } else {
                    $parsed_data = $doc->find($tag)->eq($eq[$key]);
                }
                foreach ($parsed_data as $item) {
                    $parse_arr[$key][] = pq($item)->text();
                }
            }
            phpQuery::unloadDocuments();
            return $parse_arr;
        } catch (\Exception $ex) {
            exit($ex->getMessage());
        }
    }


    public function ParseSynonym(int $limit): array
    {
        try {
            $url = $this->global_url . $this->word;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, true);

            $html = curl_exec($ch);

            curl_close($ch);

            \phpQuery::newDocument($html);

            $synonyms = pq('div.mw-parser-output')->find('a')->text();

            //$arr_synonyms = preg_split("@\n@u", $synonyms, $limit, PREG_SPLIT_NO_EMPTY);

            //var_dump($arr_synonyms);

            \phpQuery::unloadDocuments();

            return stripos('comput', $synonyms) === FALSE ? false : true;

        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return array('');
        }
    }
}
