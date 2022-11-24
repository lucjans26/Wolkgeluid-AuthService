<?php

namespace App\Traits;

trait IdTrait
{
    public static function requestTokenId()
    {
        $part1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 45);
        $part2 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 50);
        $microtime = MD5(microtime());
        //$part3 = substr(str_shuffle(MD5(microtime())), 0, 5);
        //$part3 = substr(str_shuffle(microtime()), 0, 5);
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $h = date("H");
        $mi = date("i");
        $se = date("s");
        $part3 = "$y$m$d$h$mi$se" . md5(microtime()) . '';
        return substr($part3 . $part1 . $part2, 0, 100);
    }
}
