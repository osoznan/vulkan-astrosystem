<?php
/**
 * Basic astrologic constant values, including objects which aren't changed while running
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\InfoObjects\SignInfo;
use vulkan\Core\AspectsOrbs\AspectData;
use vulkan\Core\InfoObjects\{
    AstroPointInfo,
    AspectInfo,
    HouseInfo
};
use vulkan\System\Vulkan;
use vulkan\visual\VisualAstroPoint;

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

    // basic points
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
    const NORTH_NODE = 10;
    const LILITH = 11;

    /** all aspects which may be used */
    public static $aspectInfos;

    /** information data for each zodiac sign */
    public static $signInfos;

    /** all points which may be used (except house cusps) */
    public static $pointInfos;

    /** information data for each house */
    public static $houseInfos;

    public static function readBasicElements($name = 'basic') {
        $arr = json_decode($data = file_get_contents(Vulkan::getConfig('dir.config') . '/' . $name . '.json'), 1);

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

}

/** some caching for avoiding file access and read */
if (isset($_SESSION['baseData'])) {
    list(C::$signInfos, C::$pointInfos, C::$houseInfos, C::$aspectInfos) = $_SESSION['baseData'];
} else {
    C::readBasicElements();
    $_SESSION['baseData'] = [C::$signInfos, C::$pointInfos, C::$houseInfos, C::$aspectInfos];
}