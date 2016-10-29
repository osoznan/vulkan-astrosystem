<?php
/**
 * The class with extended realization of astrological point properties
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\C;
use vulkan\System\Transfer;

class AstroPoint extends BaseAstroPoint
{
    public $chart;
    public $section;

    public $signId;
    public $houseId;

    public $orbId;

    public $speed;

    public $aspected = true;

    public $selected;

    public function __construct($pointInfo = null) {
        parent::__construct($pointInfo);
    }

    public function getParent() {
        return $this->section;
    }

    public function aspected($value) {
        $this->aspected = $value;
    }

    public function selected($value) {
        $this->selected = $value;
    }

    public function getSignId(): int {
        return floor($this->position / (M_PI / 6.0));
    }

    public function getHouseId() {
        return $this->houseId;
    }

    public function getCross() {
        return $this->signId % 3;
    }

    public function getElement() {
        return $this->signId % 4;
    }

    public function getSpeed() {
        return $this->speed;
    }

    public function isRetro(): bool {
        return $this->speed < 0;
    }

    public function isEastSign(): bool {
        return in_array($this->signId, [9, 10, 11, 0, 1, 2]);
    }

    public function isEastHouse(): bool {
        return in_array($this->houseId, [9, 10, 11, 0, 1, 2]);
    }

    public function isDaySign() {
        return $this->signId > C::VIRGO && $this->signId <= C::PISCES;
    }

    public function isDayHouse() {
        return $this->houseId > C::VIRGO && $this->houseId <= C::PISCES;
    }

    public function toArray() {
        return array_merge(
            parent::toArray(),
            Transfer::serializeObjectVars($this, 'orbId', 'speed', 'aspected', 'selected')
        );
    }

}