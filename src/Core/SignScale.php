<?php
/**
 * Predefined scale for astrological signs
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\System\Config;
use vulkan\System\C;

class SignScale extends EqualScale {

    public function __construct($params = null) {
        Config::settings($this, $params);
        $this->sectorCount(12);
    }

    /**
     * States whether a num is within 0..12
     * @param $x
     * @return float
     */
    public static function isWithinRange12($x) {
        return $x >= 0 and $x < 12;
    }

    /**
     * Gets sign ordinal num from a degree value
     * @param $degree
     * @return float
     */
    public static function signFromDegree($degree) {
        return floor($degree / 30);
    }

    /**
     * Gets sign ordinal num from a radian value
     * @param float $radian
     * @return float
     */
    public static function signFromRadian($radian) {
        return floor($radian / (M_PI / 6));
    }

    /**
     * Gets a degree in a Sign, i.e 182 => 2 (in Libra)
     * @param $degree
     * @return float
     */
    public static function degreeInSign($degree) {
        return ($degree % 30);
    }

    public static function nextSign($signNo) {
        return $signNo < 11 ? $signNo + 1 : 0;
    }

    public static function prevSign($signNo) {
        return $signNo == 0 ? 11 : $signNo - 1;
    }

    public static function oppositeSign($signNo) {
        return $signNo < 6 ? $signNo + 6 : $signNo - 6;
    }

    public static function getCross($signNo) {
        return $signNo % 3;
    }

    public static function getElement($signNo) {
        return $signNo % 4;
    }

    public static function getQuadrant($position) {
        return floor($position / PI_2);
    }

    public static function isNorth($signNo) {
        return $signNo >= 0 && $signNo <= 5;
    }
    
    public static function isEast($signNo) {
        return ($signNo >= 0 && $signNo <= 2) || ($signNo >= 9 && $signNo <= 11);
    }

    /**
     * Gets ordinal num of a sign sector where a given angle is situated,
     * where sign sector is one of the equal sign sectors which count is also specified
     * @param float $position Angle
     * @param integer $sectorCount Sector count
     * @return float
     */
    public static function getSignSector($position, $sectorCount) {
        return floor((C::radDeg($position) % 30) / (30 / $sectorCount));
    }

}