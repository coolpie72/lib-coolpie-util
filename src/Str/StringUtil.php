<?php

namespace Coolpie\Util\Str;

class StringUtil {

    public static function startsWith($str, $prefix) {
        if (empty($prefix)) return true;

        $idx = strpos($str, $prefix);
        if ($idx === false) {
            return false;
        }

        return $idx == 0;
    }
    
    //abcdef
    //012345
    //len = 6
    //ef -> pos = 4
    public static function endsWith($str, $end) {
        if (empty($end)) return true;
        
        $len = strlen($str);
        $endLen = strlen($end);
        
        $pos = $len - $endLen;
        
        $sub = substr($str, $pos, $endLen);
        
        return $sub == $end;
        
    }

    public static function chopEnd($str, $num = 1) {
        if ($str === null) return null;
        
        $len = strlen($str);
        if ($len <= $num) return "";
    
        //len = 6, chop = 2 -> 0 sebanyak $len - $num  
        //0 1 2 3 4 5
        //a b c d e f
        return substr($str, 0, $len - $num);

    }

    //ambil paling banyak num karakter dari string
    public static function getPrefix($str, $num) {
        if ($str === null) return null;

        $len = strlen($str);
        if ($len <= $num) return $str;

        return substr($str, 0, $num);
    }


}