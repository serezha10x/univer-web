<?php


namespace common\helpers;


class ViewHelper
{
    public static function formDataToDetailView(array $arr)
    {
        $print_str = '';
        foreach ($arr as $item) {
            $print_str .= ($item->value . ', ');
        }
        $print_str = rtrim($print_str, ', ');
        return $print_str;
    }
}