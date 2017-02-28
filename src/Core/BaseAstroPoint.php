<?php
/**
 * Base class for astrological point (planet, node, pars fortunae etc)
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\InfoObjects\AstroPointInfo;
use vulkan\System\Transfer;
use vulkan\System\C;

class BaseAstroPoint{

    const TYPE_POINT = 1;
    const TYPE_HOUSE = 2;

    /** @var float The longitude of a point, position on a Zodiac Circle, the main parameter surely */
    public $position;
    /** @var AstroPointInfo The InfoObject with info about a point */
    public $info;
    /** @var ChartSection Section which a point belongs to */
    public $section;

    public function __construct($pointInfo = null) {
        $this->info = $pointInfo;
    }

    /**
     * Gets the point's normalized position (range 0...2*pi)
     * @return $this
     */
    public function getPos() {
        return C::modRad($this->position);
    }

    /**
     * Sets the point's position (rad) and normalizes it
     * implied as freq. used one, that's why so short its name is
     * @return $this
     */
    public function pos($position) {
        $this->position = C::modRad($position);
        return $this;
    }

    /**
     * Gets the point's degree
     * @return float
     */
    public function getDegree() {
        return C::radDeg(C::modRad($this->position));
    }

    /**
     * Sets the point's degree, then converting into radians
     * @return $this
     */
    public function degree($position) {
        $this->position = C::degRad($position);
        return $this;
    }

    /**
     * Shifts angle of the point
     * @return $this
     */
    public function addDegree($value) {
        $this->position += C::degRad($value);
        return $this;
    }

    /**
     * Sets an object with info for the point
     * @return $this
     */
    public function info($value) {
        $this->info = $value;
        return $this;
    }

    public function id() {
        return $this->info->id;
    }

    public function section($value) {
        $this->section = $value;
        return $this;
    }
    
    public function getParent($value) {
        return $this->section;
    }

    /**
     * Gets the angle distance from this point to a given point
     * @param $point
     * @return float
     */
    public function distanceFrom($point) {
        return abs(C::modRad($this->position) - C::modRad($point->position));
    }
    
    /**
     * String representation
     * @return string
     */
    public function __toString() {
        return "{$this->info->name} ({$this->info->id}, {$this->info->caption}), " . C::radDeg($this->position) . "deg";
    }

    public function toArray() {
        return array_merge(
            $this->toArrayMinimized(),
            Transfer::serializeObjectVars($this, 'position')
        );
    }

    public function toArrayMinimized() {
        return [
            'id' => $this->id(),
            'section' => C::checkParam($this->section->key, null)
        ];
    }

}