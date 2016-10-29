<?php
/**
 * This class stores a matrix which is necessary for building charts: date, local time, gmt offset.
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\C;

class AstroMoment {

    public $dateTime;
    public $gmt;

    public function __construct($dateTime, $gmt = 0) {
        $this->dateTime($dateTime);
        $this->gmt($gmt);
    }

    public function dateTime($value) {
        if (is_string($value)){
            $this->dateTime = strtotime($value);
        } else {
            $this->dateTime = $value;
        }
        return $this;
    }

    public function gmt($value) {
        if (is_string($value)){
            $this->gmt = strtotime($value);
        } else {
            $this->gmt = $value;
        }
        return $this;
    }

    public static function isInHourRange($hour): bool{
        return $hour >= 0 && $hour <= 23;
    }

    public static function isInMinuteRange($minute): bool{
        return $minute >= 0 && $minute <= 59;
    }

    public static function hourMinuteString($h, $m) {
        if (self::isInHourRange($h) && self::isInMinuteRange($m)) {
            return sprintf("%02d:%02d", $h, $m);
        } else {
            throw new \Exception(_('Wrong time format'));
        }
    }

    function toArray() {
        return [
            'datetime' => $this->datetime,
            'gmt' => $this->gmt
        ];
    }
}