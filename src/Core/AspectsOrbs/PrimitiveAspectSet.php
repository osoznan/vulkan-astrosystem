<?php
/**
 * The simpliest aspect/orb table with a single orb value for all points/aspects
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @subpackage Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\Core\C;
use vulkan\Core\Vulkan;

/*
 * class denoting a single orb value for ALL points of ALL aspects
 */

class PrimitiveAspectSet extends AspectSet {

    public function __construct($params = []) {
        $this->columnCount = 1;
        $this->aspects($params[0] ?? C::$aspectInfos);
        $this->orbData($params[1] ?? 1);
    }

    function orbData($param = null) {
        foreach ($this->aspects as $aspect) {
            $this->data[$aspect->id] = new AspectData($aspect->id, [$param]);
        }
        //Vulkan::d(C::$aspectInfos);
        return $this;
    }

    function orbIds($points) {
        foreach ($points as $point) {
            $point->orbId = 0;
        }
        return $this;
    }
}