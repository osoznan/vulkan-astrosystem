<?php
/**
 * Class for location (latitude, longitude, place name etc.)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\C;
use vulkan\Helpers\Fmt;

class Location {
    public $latitude;
    public $longitude;

    public $geoId;
    public $name;

    public function __construct($longitude, $latitude) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function longitude($value) {
        if (is_numeric($value)) {
            $this->longitude = $value;
        } else {
            $this->longitude = self::longitudeToFloat();
        }
    }

    public function latitude($value) {
        if (is_numeric($value)) {
            $this->latitude = $value;
        } else {
            $this->latitude = self::latitudeToFloat();
        }
    }

    public function isNorth() {
        return $this->latitude > 0;
    }

    public function isEast() {
        return $this->longitude > 0;
    }

    public static function floatToLongitude($decimal, $format = null) {
        $letter = $decimal >= 0 ? 'e' : 'w';
        $format = $format ?? ('%03d' . $letter . '%02d\'%02d"');
        return Fmt::floatToDegMinSec(abs($decimal), $format);
    }

    public static function floatToLatitude($decimal, $format = null) {
        $letter = $decimal >= 0 ? 'n' : 's';
        $format = $format ?? ('%02d' . $letter . '%02d\'%02d"');
        return Fmt::floatToDegMinSec(abs($decimal), $format);
    }

    public function longitudeToFloat() {

    }

    public function latitudeToFloat() {

    }
}