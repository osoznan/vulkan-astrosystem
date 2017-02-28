<?php
/**
 * The simpliest aspect/orb table with a single orb value for all points/aspects
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

/*
 * class denoting an aspect set with single orb value for an aspect.
 */
use vulkan\System\Config;

class PrimitiveAspectSet extends AspectSet {

    public function __construct($data = null) {
        parent::__construct($data);
        $this->orbs = [[0]];
    }

    function orbIds($points) {
        foreach ($points as $point) {
            $point->orbId = 0;
        }
        return $this;
    }
}