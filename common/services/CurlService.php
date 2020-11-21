<?php

namespace common\services;

class CurlService
{
    public function getPageText($url)
    {
        $ch = curl_init($url);
        $useragent = "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'REMOTE_ADDR' => "127.0.0.1",
            'X-NewRelic-ID' => "VQUFVVRACQEEUlAS",
            'X-Requested-With' => "XMLHttpRequest"
        ));
        $postData = array(
            'login' => 'serezha10x',
            'password' => 'kanatush1234',
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $html = curl_exec($ch);

        curl_close($ch);
        return $html;
    }

    public function downloadFile($url, $path)
    {
        $ch = curl_init($url);

        $fp = fopen($path, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 7);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_exec($ch);

        curl_close($ch);

        fclose($fp);
    }
}