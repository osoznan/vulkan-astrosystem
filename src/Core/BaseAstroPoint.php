<?php
/**
 * Base class for astrological point (planet, node, pars fortunae etc)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\System\Transfer;

class BaseAstroPoint
{

    public $position;
    public $info;

    public $section;

    public function __construct($pointInfo = null) {
        $this->info = $pointInfo;
    }

    public function getPos() {
        return modRad($this->position);
    }

    // implied as freq. used one, that's why so short its name is
    public function pos($position) {
        $this->position = modRad($position);
        return $this;
    }

    public function getDegree() {
        return radDeg(modRad($this->position));
    }

    public function degree($position) {
        $this->position = degRad($position);
        return $this;
    }

    public function addDegree($value) {
        $this->position += degRad($value);
        return $this;
    }

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

    public function distanceFrom($point) {
        return abs(modRad($this->position) - modRad($point->position));
    }

    public function isPlanet($point) {
        return $this->info->id >=0 && $this->info->id <= 9;
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
            'section' => $this->section->key ?? null
        ];
    }

}