<?php


namespace common\helpers;


class CommonHelper
{
    public static function getUrlQuery($url, $key = null)
    {
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            if (is_null($key)) {

                return $query;
            } elseif (isset($query[$key])) {

                return $query[$key];
            }
        }

        return false;
    }

    public static function ikey_exist(string $key, array $arr)
    {
        foreach ($arr as $arr_key => $value) {
            if (strcasecmp($key, $arr_key)) {
                var_dump($key, $arr_key);
                return true;
            }
        }

        return false;
    }
}