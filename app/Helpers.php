<?php

if (!function_exists('toThaiNumber')) {
    function toThaiNumber($number) {
        return strtr(number_format((float)$number), [
            '0' => '๐', '1' => '๑', '2' => '๒', '3' => '๓', '4' => '๔',
            '5' => '๕', '6' => '๖', '7' => '๗', '8' => '๘', '9' => '๙'
        ]);
    }
}

if (!function_exists('formatDateThai')) {
    function formatDateThai($date) {
        if (!$date) return '-';
        
        $thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];

        $carbonDate = \Carbon\Carbon::parse($date);
        $day = toThaiNumber($carbonDate->format('d'));
        $month = $thaiMonths[$carbonDate->month - 1];
        $year = strtr($carbonDate->year + 543, [
            '0' => '๐', '1' => '๑', '2' => '๒', '3' => '๓', '4' => '๔',
            '5' => '๕', '6' => '๖', '7' => '๗', '8' => '๘', '9' => '๙'
        ]);

        return "วันที่ {$day} เดือน {$month} พ.ศ.{$year}";
    }
}

if (!function_exists('toThaiNumberOnly')) {
    function toThaiNumberOnly($text) {
        return preg_replace_callback('/\d/', function ($matches) {
            $thaiNumbers = ['0'=>'๐', '1'=>'๑', '2'=>'๒', '3'=>'๓', '4'=>'๔', '5'=>'๕', '6'=>'๖', '7'=>'๗', '8'=>'๘', '9'=>'๙'];
            return $thaiNumbers[$matches[0]];
        }, $text);
    }
}

