<?php

namespace common\services\wiki;

use Clue\React\Buzz\Browser;
use \Symfony\Component\DomCrawler\Crawler;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server;

class WikipediaApi
{

    function GetWikiPage($query) {
        $endPoint = 'https://en.wiktionary.org/w/api.php?action';
        $params = [
            'formatversion' => '1',
            'action' => 'parse',
            'page' => $query,
            'format' => 'json'
        ];

        $url = $endPoint . '?' . http_build_query( $params );

        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $output = curl_exec( $ch );
        curl_close( $ch );

        $result = json_decode( $output, true );

        return $result['parse']['text']['*'];
    }


    public function WikiClient($title)
    {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => $reqdata = http_build_query(array(
                    'action' => 'query',
                    'list' => 'search',
                    'srsearch' => $title,
                    'format' => 'json'
                )),
                'header' => implode("\r\n", array(
                    "Content-Length: " . strlen($reqdata),
                    "User-Agent: MyCuteBot/0.1",
                    "Connection: Close",
                    ""
                ))
            ))
        );

        if (false === $response = file_get_contents("https://en.wiktionary.org/w/api.php", false, $context)) {
            return false;
        }
        //парсим строку
        $json = json_decode($response, JSON_UNESCAPED_UNICODE);
        //echo "<pre>";var_dump($json); exit();
        return $json;
    }


    public function gtranslate($str, $lang_from, $lang_to) {
        $query_data = array(
            'client' => 'x',
            'q' => $str,
            'sl' => $lang_from,
            'tl' => $lang_to
        );
        $filename = 'http://translate.google.ru/translate_a/t';
        $options = array(
            'http' => array(
                'user_agent' => 'Mozilla/5.0 (Windows NT 6.0; rv:26.0) Gecko/20100101 Firefox/26.0',
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($query_data)
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents($filename, false, $context);
        return json_decode($response);
    }


    public function wikitionary2() {

        $loop = React\EventLoop\Factory::create();
        $client = new Browser($loop);
        $client->get('https://www.kinopoisk.ru/film/1009536/')
            ->then(function(\Psr\Http\Message\ResponseInterface $response) {
                $document = new \DiDom\Document($response->getBody()->__toString());
            });
        $client->get('https://en.wiktionary.org/wiki/PHP')
            ->then(function(\Psr\Http\Message\ResponseInterface $response) {
                echo $response->getBody() . PHP_EOL;
            });
        exit();
    }


    public function wiktionary($query): bool {

        //        $url = 'https://en.wiktionary.org/w/index.php?title='. $query .'&printable=yes';
        $url = 'https://en.wiktionary.org/wiki/'.$query;

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, true);

            $html = curl_exec($ch);
            //echo $html;
            curl_close($ch);

            \phpQuery::newDocument($html);

        $synonyms = pq('html body div#content.mw-body div#bodyContent.mw-body-content div#mw-content-text.mw-content-ltr div.mw-parser-output ol li span.ib-content')->find('a')->text();


            var_dump($synonyms);

            \phpQuery::unloadDocuments();
        //$filename = 'https://ru.wiktionary.org/wiki/MYSQL';
        //$response = file_get_contents($filename);
        //$xml = simplexml_load_file($filename) or die("asd");
        //var_dump($xml);
//        $xml = new SimpleXMLElement($response);
        //$parser = xml_parser_create();
        //xml_parse($parser, $response);

        return (stripos($synonyms, 'comput') === false ? false : true OR stripos($synonyms, 'program') === false ? false : true OR stripos($synonyms, 'system') === false ? false : true);
    }



    public function semantic() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://google-search3.p.rapidapi.com/api/v1/crawl",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{ \"country\": \"US\", \"get_total\": false, \"hl\": \"us\", \"language\": \"lang_en\", \"max_results\": 100, \"q\": \"musql\", \"uule\": \"\"}",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json",
                "x-rapidapi-host: google-search3.p.rapidapi.com",
                "x-rapidapi-key: 4bc63fb9abmsh9e031859e23a6e6p1e3f7ejsn552633da723d"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response; exit();
        }
    }
}
