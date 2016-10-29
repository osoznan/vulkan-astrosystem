<?php
/**
 * Application is the class which runs the Vulkan AstroSystem (controllers-views), a starting point of it
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

use vulkan\System\Vulkan;

class Application {

    public $name = 'My Vulkan AstroSystem Application';

    public $config;

    /* @var $controller Controller */
    public $controller;

    public function run($config, $controllerName = null, $actionName = null) {

        $this->config = $config;
        Vulkan::$app = $this;

        $controllerClass = Vulkan::getConfig('controller.class');
        $ctrlName = $controllerClass::$nameSpace . DIR_SLASH . $controllerName;
        $this->controller = new $ctrlName;
        $this->controller->frame = $this->controller->frame ?? 'frame_default';

        $this->controller->run($actionName);
    }

}