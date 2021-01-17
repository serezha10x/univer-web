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

    public static function getKeywordsFromQuery(string $query): array
    {
        return preg_split("@[^A-Za-zА-Яа-я]+@u", $query);
    }

    public static function getVsmFromQuery($query)
    {
        $query = CommonHelper::getKeywordsFromQuery($query);
        $query = array_map('mb_strtoupper', $query);
        $queryVsm = [];

        foreach ($query as $word) {
            $queryVsm[$word] = 1;
        }

        return $queryVsm;
    }
}