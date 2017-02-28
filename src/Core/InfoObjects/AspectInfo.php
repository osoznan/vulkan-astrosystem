<?php
/**
 * Aspect info, necessary for storing each of defined aspects
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\InfoObjects
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

use vulkan\System\C;

class AspectInfo extends InfoObject
{
    /** @var float Angle in radians */
    public $angle;
    /** @var float Angle in degrees */
    public $angleDegree;

    /**
     * Initialization of aspect info
     * @param integer $id Aspect id
     * @param string $name Name, i.e. "trine"
     * @param float $angleDegree Angle of aspect in degrees, it becomes an alias
     * @param string $caption Aspect caption
    */
    public function __construct($id, $name, $angleDegree, $caption)
    {
        parent::__construct($id, $name, intVal($angleDegree));
        $this->angleDegree = $angleDegree;
        $this->angle = $angleDegree * _2PI / 360;
        $this->caption = $caption;
    }

}