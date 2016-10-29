<?php
/**
 * Scale for a Zodiac circle, which describes any scale with equal divisions (i.e. Zodiac Sign scale
 * with 12 divisions or a degree scale with 360 divisions).
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\C;
use vulkan\System\Config;

class EqualScale extends SectionScale {

    // angle between adjacent positions
    public $sectorAngle;

    public function __construct($params = null) {
        if (is_numeric($params)) {
            $this->sectorCount($params);
        } else {
            Config::settings($this, $params);
            if ($this->sectorCount) {
                $this->sectorCount($this->sectorCount());
            }
        }
    }

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
    }

    public function sectorIndexFromAngle($angle) {
        return floor(modRad($angle) / $this->sectorAngle);
    }

    public static function getSectors($points, $sectorCount) {
        $sectorAngle = _2PI / $sectorCount;
        $result = [];
        foreach ($points as $point) {
            $result[$point->info->id] = floor(modRad($point->position) / $sectorAngle);
        }
        return $result;
    }

}