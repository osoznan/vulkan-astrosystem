<?php
/**
 * Scale for a Zodiac circle, which describes any scale with equal divisions (i.e. Zodiac Sign scale
 * with 12 divisions or a degree scale with 360 divisions).
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use \vulkan\System\C;

class EqualScale extends SectionScale {

    /** @var float Angle between adjacent positions */
    public $sectorAngle;

    public function __construct($params = null) {
        if (is_numeric($params)) {
            $this->sectorCount($params);
        } elseif ($params) {
            Config::settings($this, $params);
            if ($this->sectorCount) {
                $this->sectorCount($this->sectorCount);
            }
        }
    }

    /**
     * Sets the sectorCount
     * @param integer $sectorCount
     * @return $this
     */
    public function sectorCount($sectorCount) {
        $this->sectorCount = $sectorCount;
        $this->sectorAngle = _2PI / $sectorCount;
        $angle = 0;
        $this->positions = [];
        for ($i = 0; $i < $sectorCount; $i++) {
            $this->positions[] = $angle;
            $angle += $this->sectorAngle;
        }
        $this->sectorAngle = _2PI / $this->sectorCount;
        return $this;
    }

    /**
     * Gets the sector ordinal number for an angle
     * @param float $angle
     * @return float Sector index
     */
    public function sectorIndexFromAngle($angle) {
        return floor(C::modRad($angle) / $this->sectorAngle);
    }

    /**
     * Gets angles of equal divisions which divide a circle with a given $sectorCount
     * @param float $angle
     * @return array Angle positions
     */
    public static function getSectors($points, $sectorCount) {
        $sectorAngle = _2PI / $sectorCount;
        $result = [];
        foreach ($points as $point) {
            $result[$point->info->id] = floor(modRad($point->position) / $sectorAngle);
        }
        return $result;
    }

}