<?php
/**
 * Aspect info, necessary for storing each of defined aspects
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

use vulkan\Core\C;

class AspectInfo extends InfoObject
{
    public $angle;
    public $angleDegree;

    public $caption;

    public function __construct($id, $name, $angleDegree, $caption)
    {
        parent::__construct($id, $name, intVal($angleDegree));
        $this->angleDegree = $angleDegree;
        $this->angle = $angleDegree * _2PI / 360;
        $this->caption = $caption;
    }

    public function __toString() {
        return $this->name;
    }

}