<?php
/**
 * The class with extended realization of astrological point properties, used for sky bodies and fictional sky points (i.e. nodes)
 * Adds routines related to a point's sign/house, flags of selection/aspectation etc.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\System\C;
use vulkan\System\Transfer;

class AstroPoint extends BaseAstroPoint
{
    public $chart;

    /** @var integer A sign ordinal */
    public $signId;
    /** @var integer A house ordinal */
    public $houseId;
    /** @var integer An orb column id of attached AspectSet */
    public $orbId;
    /** @var float Speed of going around */
    public $speed;
    
    /** @var boolean whether the point is aspected */
    public $aspected = true;
    /** @var boolean whether the point is to select (a flag for distinguishing)*/
    public $selected;

    public function __construct($pointInfo = null) {
        parent::__construct($pointInfo);
    }

    public function getKind() {
        return static::TYPE_POINT;
    }

    public function aspected($value) {
        $this->aspected = $value;
        return $this;
    }

    public function selected($value) {
        $this->selected = $value;
        return $this;
    }

    /**
     * Gets ordinal number of the point's sign
     * @return integer Sign No
     */
    public function getSignId() {
        return floor(C::modRad($this->position) / PI_6);
    }

    /**
     * Gets ordinal number of the point's house
     * @return integer House No
     */
    public function getHouseId($houses = null) {
        $houses = C::checkParam($houses, $this->section->houses);
        return C::getHouse($this->position, $houses);
    }

    /**
     * Gets whether the point is retrograde
     * @return boolean
     */
    public function isRetro() {
        return $this->speed < 0;
    }

    public function toArray() {
        return array_merge(
            parent::toArray(),
            Transfer::serializeObjectVars($this, 'orbId', 'speed', 'aspected', 'selected', 'houseId'),
            ['signId' => $this->getSignId()]
        );
    }

}