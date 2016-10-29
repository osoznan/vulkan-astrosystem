<?php
/**
 * Scale for astrological signs
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

class SignScale extends EqualScale {

    public function __construct() {
        $this->sectorCount(12);
    }

    public static function signFromDegree($degree): int{
        return floor(modDeg($degree) / 30);
    }

    public function signFromRadian($radian): int{
        return floor(modRad($radian) / (M_PI / 6));
    }

    public static function degreeInSign($degree): float{
        return ($degree % 30);
    }

    public static function nextSign($signNo): int{
        return $signNo < 11 ? $signNo + 1 : 0;
    }

    public static function prevSign($signNo): int{
        return $signNo < 0 ? 11 : $signNo - 1;
    }

    public static function oppositeSign($signNo): int{
        return $signNo < 6 ? $signNo + 6 : $signNo - 6;
    }

    public static function getCross($signNo): int{
        return $signNo % 3;
    }

    public static function getElement($signNo): int{
        return $signNo % 4;
    }

    public static function getQuadrant($position) {
        return floor($position / PI_2);
    }

    public static function getSignSector($position, $sectorCount) {
        return floor(($position % PI_6) / $sectorCount);
    }


}