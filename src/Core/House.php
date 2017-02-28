<?php
/**
 * Class for an astrological House
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\System\C;

class House extends AstroPoint {

    public function getKind() {
        return static::TYPE_HOUSE;
    }

    /**
     * Sets the section
     * @param object $value Section instance
     * @return $this
     */
    public function section($value) {
        $this->section = $value;
        return $this;
    }

    /**
     * Gets an angle between this house to the next house
     * @return float Angle
     */
    public function width($houses = null) {
        $houses = C::checkParam($houses, $this->section->houses);
        return $this->distanceFrom($houses[SignScale::nextSign($this->info->id)]);
    }

    /**
     * Zodiac Signs (their ids) which are involved in this house
      * @return array Sign ids list
     */
    public function includedSigns()
    {
        //TODO
    }

}