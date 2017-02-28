<?php
/**
 * An astrological info for building a horoscope. Title, description, moment (date, time, gmt), location (longitude,
 * latitude, location name)
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\System\Config;
use vulkan\System\Transfer;

class ChartSectionInfo {

    /** @var \vulkan\Core\AstroMoment Object with date-time-gmt info */
    public $moment;
    /** @var \vulkan\Core\Location Object with location info */
    public $location;

    /** @var string Title of a section */
    public $title;
    /** @var string Description text */
    public $description;

    public function construct($params = null) {
        Config::settings($this, $params);
    }

    /**
     * Sets date && time
     * @param mixed $value Datetime str value (i.e. 1980-01-01 10:30:00) or a timestamp value
     * @param mixed|null $gmt Gmt time offset (i.e. 3:20) or a timestamp value
     * @return $this
     */
    public function dateTime($value, $gmt = null) {
        $this->moment = new AstroMoment($value, $gmt);
        return $this;
    }

    public function gmt($value) {
        if ($this->moment) {
            $this->moment->gmt($value);
        }
    }

    /**
     * Sets location params
     * @param float|array $lon Longitude (decimal format), if >0 => eastern, <0 => western OR array [$lon, $lat]
     * @param float $lat Latitude (decimal format), if >0 => northern, <0 => southern
     * @param string|null $name Location name, i.e. "Moscow, Russia"
     * @return $this
     */
    public function location($lon, $lat = null, $name = null) {
        if (is_array($lon)) {
            $this->location = new Location($lon[0], $lon[1]);
        } else {
            $this->location = new Location($lon, $lat);
        }
        return $this;
    }

    public function toArray() {
        return Transfer::serializeObjectVars($this, 'moment', 'location', 'title');
    }
}