<?php

class Sorter {

    private static function mapPolish($str) {
        $map = [
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l',
            'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z',
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'E', 'Ł' => 'L',
            'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S', 'Ź' => 'Z', 'Ż' => 'Z'
        ];
        return strtr($str, $map);
    }

    static function sort(&$result) {
        usort($result, function ($a, $b) {
            return strcmp(Sorter::mapPolish($a['title']), Sorter::mapPolish($b['title']));
        });

    }

}