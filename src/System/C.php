<?php
/**
 * Basic astrologic constant values and widely used functions, including objects which aren't changed while running
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

use vulkan\Core\InfoObjects\AspectInfo;
use vulkan\Core\InfoObjects\AstroPointInfo;
use vulkan\Core\InfoObjects\HouseInfo;
use vulkan\Core\InfoObjects\SignInfo;
use vulkan\System\Vulkan;

define('PI', M_PI);
define('PI_2', M_PI / 2);
define('PI_6', M_PI / 6);
define('_2PI', M_PI * 2);

class C
{
    //signs const
    const ARIES = 0;
    const TAURUS = 1;
    const GEMINI = 2;
    const CANCER = 3;
    const LEO = 4;
    const VIRGO = 5;
    const LIBRA = 6;
    const SCORPIO = 7;
    const SAGITTARIUS = 8;
    const CAPRICORN = 9;
    const AQUARIUS = 10;
    const PISCES = 11;

    // basic points (planets)
    const SUN = 0;
    const MOON = 1;
    const MERCURY = 2;
    const VENUS = 3;
    const MARS = 4;
    const JUPITER = 5;
    const SATURN = 6;
    const URANUS = 7;
    const NEPTUNE = 8;
    const PLUTO = 9;

    /** @var AspectInfo[] $aspectInfos all aspects which may be used */
    public static $aspectInfos;

    /** @var SignInfo[] $signInfos information data for each zodiac sign */
    public static $signInfos;

    /** @var AstroPointInfo[] $pointInfos all points which may be used (except house cusps) */
    public static $pointInfos;

    /** @var HouseInfo[] $houseInfos information data for each house */
    public static $houseInfos;

    /**
     * Gets the basic "const elements" from a json file
     * @param $name string|'basic' Filename without extension, file must be in a config dir.
     * @return BaseAstroPoint
     */
    public static function readBasicElements($name = 'basic') {
        $arr = json_decode($data = file_get_contents(Vulkan::getConfig('dir.config') . $name . '.json'), 1);

        if (!$arr) {
            throw new \Exception(_('error loading config file'));
        }

        self::$signInfos = [];
        array_walk($arr['signs'], function($el, $id) {
            self::$signInfos[] = new SignInfo($id, $el['name'], $el['alias'], $el['caption']);
        });

        self::$pointInfos = [];
        array_walk($arr['points'], function($el, $id) {
            self::$pointInfos[] = new AstroPointInfo($id, $el['name'], $el['alias'], $el['caption']);
        });

        self::$houseInfos = [];
        array_walk($arr['houses'], function($el, $id) {
            self::$houseInfos[] = new HouseInfo($id, $el['name'], $el['alias'], $el['caption']);
        });

        self::$aspectInfos = [];
        array_walk($arr['aspects'], function($el, $id) {
            self::$aspectInfos[] = new AspectInfo($id, $el['name'], $el['angle'], $el['caption']);
        });

    }

    public static function toArray() {
        return [
            'signInfos' => self::$signInfos,
            'pointInfos' => self::$pointInfos,
            'houseInfos' => self::$houseInfos,
            'aspectInfos' => self::$aspectInfos,
        ];
    }

//---------------------------------------------------------------------------------

    public static function frac($x) {
        $x = abs($x);
        return $x - floor($x);
    }

    public static function sign($x) {
        return ($x > 0) - ($x < 0);
    }

    public static function radDeg($x) {
        return $x * 180 / PI;
    }

    public static function degRad($x) {
        return $x * PI / 180;
    }
    public static function normalizeByAscRad(&$min, &$max) {
        if ($max < $min) {
            $max += _2PI;
        }
    }

#----------------------------------------------------------------------
    public static function decimalToMinutes($x) {
        return $x * 60.0 / 100.0;
    }

    public static function minutesToDecimal($x) {
        return $x * 100.0 / 60;
    }

    public static function floatToDegMinSec($degree) {
        $degInt = intval(abs($degree));
        $minutes = (abs($degree) - $degInt) * 60;
        $seconds = ($minutes - intval($minutes)) * 60;
        return [intval($degree), intval(round($minutes, 5)), round($seconds) == 60 ? 0: floor($seconds), ($degree < 0 ? -1 : 1)];
    }

#-----------------------------------------------------------
    /**
     * Normalizes a degree angle to the 0...2*PI range
     * @param float $degree
     * @return float
     */
    public static function modDeg($degree) {
        while ($degree > 360) {
            $degree -= 360;
        }
        while ($degree < 0) {
            $degree += 360;
        }
        return $degree;
    }

    /**
     * Normalizes a radian angle to the 0...2*PI range
     * @param float $radian
     * @return float
     */
    public static function modRad($radian){
        while ($radian > _2PI) {
            $radian -= _2PI;
        }
        while ($radian < 0) {
            $radian += _2PI;
        }
        return $radian;
    }

    /**
     * Normalizes a degree angle to the 0...180 range
     * @param float $degree
     * @return float
     */
    public static function mod180($degree) {
        while ($degree > 180) {
            $degree -= 180;
        }
        while ($degree < 0) {
            $degree += 180;
        }
        return $degree;
    }

    /**
     * Normalizes a radian angle to the 0...PI range
     * @param float $radian
     * @return float
     */
    public static function modPi($radian){
        while ($radian > PI) {
            $radian -= PI;
        }
        while ($radian < 0) {
            $radian += PI;
        }
        return $radian;
    }

    public static function isInRange($min, $max, $value) {
        return $value >= $min && $value <= $max;
    }

// ------------------------------------------------------
    public static function getHouse($position, $houses) {
        $i = 0;
        do {
            $current = current($houses)->position;
            $next = next($houses);
            if (!$next) {
                return 11;
            } elseif ($next->position < $current) {
                $next->position += _2PI;
            } else
                if ($position >= $current && $position < $next->position) {
                    return $i;
                }
            $i++;
        } while (true);
    }

    public static function checkParam($value, $ifNull = null) {
        if (isset($value)) {
            return $value;
        } else {
            return $ifNull;
        }
    }
}

/** some caching for avoiding file access and read */
if (isset($_SESSION['baseData'])) {
    list(C::$signInfos, C::$pointInfos, C::$houseInfos, C::$aspectInfos) = $_SESSION['baseData'];
} else {
    C::readBasicElements();
    $_SESSION['baseData'] = [C::$signInfos, C::$pointInfos, C::$houseInfos, C::$aspectInfos];
}