<?php
/**
 * Class for an astrological House
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

class House extends BaseAstroPoint {

    public $section;

    public function section($value) {
        $this->section = $value;
        return $this;
    }

    public function getHouseId() {
        return $this->info->id;
    }

    public function getHouse() {
        return $this->info;
    }

    public function width(): float {
        $distance = abs($this->position - $this->section->getHouses[nextSign($this->_info->id)]);
        return min($distance, _2PI - $distance);
    }

    public function includedSigns()
    {
        $sign1 = $this->signId;
        $sign2 = $this->section->getHouses[nextSign($this->_info->id)] . $signId;

        if ($sign1 == $sign2) {
            return [$sign1];
        }
    }

}