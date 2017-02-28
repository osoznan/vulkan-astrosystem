<?php
/**
 * Class for location (latitude, longitude, place name etc.)
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Helpers\Fmt;

class Location {
    /** @var float Latitude value in a decimal fmt */
    public $latitude;
    /** @var float Longitude value in a decimal fmt */
    public $longitude;
    /** @var integer ID of a location item (of a city, village etc.); for realization in the future */
    public $geoId;
    /** @var string Name of a location */
    public $name;

    public function __construct($longitude, $latitude, $geoId = null, $name = null) {
        list($this->longitude, $this->latitude, $this->geoId, $this->name) = [$longitude, $latitude, $geoId, $name];
    }

    /**
     * sets a longitude
     * @param $value float Decimal value of a longitude angle
     * @return $this
     */
    public function longitude($value) {
        $this->longitude = $value;
        return $this;
    }

    /**
     * Sets a latitude
     * @param $value float Decimal value of a longitude angle
     */
    public function latitude($value) {
        $this->latitude = $value;
    }

    /**
     * Gets whether latitude is northern
     * @return boolean
     */
    public function isNorth() {
        return $this->latitude >= 0;
    }

    /**
     * Gets whether longitude is eastearn
     * @return boolean
     */
    public function isEast() {
        return $this->longitude >= 0;
    }

    /**
     * Gets formatted represantation (deg [e|w] min'sec") for a longitude
     * @return string
     */
    public static function floatToLongitude($decimal) {
        $letter = $decimal >= 0 ? 'e' : 'w';
        return Fmt::floatToDegMinSec(abs($decimal), '%03d' . $letter . '%02d\'%02d"');
    }

    /**
     * Gets formatted represantation (deg [n|s] min'sec") for a latitude
     * @return string
     */
    public static function floatToLatitude($decimal) {
        $letter = $decimal >= 0 ? 'n' : 's';
        return Fmt::floatToDegMinSec(abs($decimal), '%02d' . $letter . '%02d\'%02d"');
    }

    // 038e23.00, 01w21.00
    public static function longitudeStrToNumber($s) {
        if (!$data = self::splitLongitudeString($s)) {
            return false;
        }
        return $data['sign'] * ($data['deg'] + $data['min'] / 60 + $data['sec'] / 3600);
    }

    // 38n23.00, 01s21.00
    public static function latitudeStrToNumber($s) {
        if (!$data = self::splitLatitudeString($s)) {
            return false;
        }
        return $data['sign'] * ($data['deg'] + $data['min'] / 60 + $data['sec'] / 3600);
    }

    public static function splitLongitudeString($s) {
        if (!$s = self::checkLongitudeString($s)) {
            return false;
        }
        return [
            'sign' => $s[0], 'deg' => $s[1], 'min' => $s[2], 'sec' => $s[3]
        ];
    }

    public static function splitLatitudeString($s) {
        if (!$s = self::checkLatitudeString($s)) {
            return false;
        }
        return [
            'sign' => $s[0], 'deg' => $s[1], 'min' => $s[2], 'sec' => $s[3]
        ];
    }

    public static function checkLongitudeString($s) {
        if (strlen($s) != 9 && $s[6] != '.') {
            return false;
        }
        if ($s[3] == 'e') {
            $sign = 1;
        } elseif ($s[3] == 'w') {
            $sign = -1;
        } else {
            return false;
        }
        $nums = [$sign, substr($s, 0, 3), substr($s, 4, 2), substr($s, 7, 2)];
        foreach ($nums as $num) {
            if (!is_numeric($num)) {
                return false;
            }
        }
        return $nums;
    }

    public static function checkLatitudeString($s) {
        if (strlen($s) != 8 || $s[5] != '.') {
            return false;
        }
        if ($s[2] == 'n') {
            $sign = 1;
        } elseif ($s[2] == 's') {
            $sign = -1;
        } else {
            return false;
        }
        $nums = [$sign, substr($s, 0, 2), substr($s, 3, 2), substr($s, 6, 2)];
        foreach ($nums as $num) {
            if (!is_numeric($num)) {
                return false;
            }
        }
        return $nums;
    }

}