<?php
/**
 * An astrologic info for building a horoscope. Title, description, moment (date, time, gmt), location (longitude,
 * latitude, location name)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\BaseGeoLocation;
use vulkan\Core\C;
use vulkan\System\Config;
use vulkan\System\Transfer;

class ChartSectionInfo {

    public $moment;
    public $location;

    public $title;
    public $description;

    public function dateTime($value, $gmt = null) {
        $this->moment = new AstroMoment($value, $gmt);
        return $this;
    }

    public function location($lon, $lat) {
        $this->location = new Location($lon, $lat);
        return $this;
    }

    public function toArray() {
        return Transfer::serializeObjectVars($this, 'moment', 'location', 'title');
    }
}