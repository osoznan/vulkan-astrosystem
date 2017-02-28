<?php
/**
 * Formatting helper functions
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Helpers;

use vulkan\System\C;

class Fmt
{
    public static function floatToDegMinSec($decimal, $fmtString = '%d°%02d\'%02d"') {
        $parts = C::floatToDegMinSec($decimal);
        return sprintf($fmtString, $parts[0], $parts[1], $parts[2]);
    }

    public static function degMinToFloat($decimal) {
        $parts = explode('°', substr($decimal, 0, count($decimal) - 1));
        return $parts[0] . '.' . $parts[1];
    }

    public static function floatToDegSignMin($degree) {
        $parts = C::floatToDegMinSec(C::modDeg($degree));
        $sign = C::$signInfos[intval($parts[0] / 30)]->caption;
        return sprintf('%02d%s%02d\'%02d\'\'', $parts[3] * ($parts[0] % 30), $sign, $parts[1], $parts[2]);
    }

    public static function DegMinSecToFloat($decimal, $fmtString = '%d°%02d\'%02d"') {
        $parts = C::floatToDegMinSec($decimal);
        return sprintf($fmtString, $parts[3] * $parts[0], $parts[1], $parts[2]);
    }

}