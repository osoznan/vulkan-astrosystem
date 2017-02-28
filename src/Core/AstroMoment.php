<?php
/**
 * This class stores a moment data which is necessary for building charts: date, local time, gmt offset.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

class AstroMoment {

    /** @var integer Timestamp of date && time */
    public $dateTime;
    /** @var integer Timestamp of a GMT offset */
    public $gmt;

    public function __construct($dateTime, $gmt = 0) {
        $this->dateTime($dateTime);
        $this->gmt($gmt);
    }

    /**
     * Sets dateTime value
     * @param $value
     * @return $this
     */
    public function dateTime($value) {
        if (is_string($value)){
                $this->dateTime = strtotime($value);
        } else {
            $this->dateTime = $value;
        }
        return $this;
    }

    /**
     * Sets gmt value
     * @param $value
     * @return $this
     */
    public function gmt($value) {
        if (!is_numeric($value)){
            $this->gmt = strtotime($value);
        } else {
            $this->gmt = $value;
        }
        return $this;
    }

    /**
     * Checks if a value in 0...23 range
     * @param $value
     * @return bool
     */
    public static function isInHourRange($value) {
        return $value >= 0 && $value <= 23;
    }

    /**
     * Checks if a value in 0...59 range
     * @param $value
     * @return bool
     */
    public static function isInMinuteRange($value) {
        return $value >= 0 && $value <= 59;
    }

    function toArray() {
        return [
            'datetime' => $this->dateTime,
            'gmt' => $this->gmt
        ];
    }
}