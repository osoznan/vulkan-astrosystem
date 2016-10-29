<?php
/**
 * A Common Chart Widget (Single Section Chart)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package User Components
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\front\Components;

use vulkan\System\Config;

class GeneralSingleChart extends \vulkan\System\Widget {

    public function __construct($params = null) {
        parent::__construct($params);
        $this->chart = new \vulkan\front\Components\GeneralSingleChart\GeneralSingleChart($params);
        Config::settings($this->chart, $params);
       // Config::copyProperties($this, $this->chart);
    }

    public function run($params = null) {


        return $this->chart->run($this->width, $this->height ?? $this->width);
    }

}