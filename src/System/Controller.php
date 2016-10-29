<?php
/**
 * Base class for the Vulkan controllers
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\System;

use vulkan\Core\ChartSection;
use vulkan\System\Vulkan;
use vulkan\Helpers\Strings;

class Controller {

    protected $_view;
    public $name;

    public $frame;

    public static $nameSpace;

    public $defaultAction;

    public function __construct() {
        $this->name = Strings::shortClassName(get_called_class());
        $this->init();
    }

    public function beforeAction($action) {
        $this->getView()->addCssFile('default-chart.css');

    }

    public function afterAction($action) {}

    /* @return \vulkan\Core\View */
    public function getView() {
        if (!$this->_view) {
            $view = Vulkan::getConfig('view.class');
            $this->_view = new $view();
        }
        return $this->_view;
    }

    public function render($name, $params = []) {
        return $this->getView()->render($this->name . '\\' . $name);
    }

    public function run($action = null) {
        $this->action = 'action' . ($action ?? 'index');

        $this->beforeAction($action);

        if (method_exists($this, $this->action)) {
            $content = $this->{$this->action}();
            $this->getView()->renderAll($content);
        } else {
            throw new \ErrorException(_('Action doesn\'t exist or wrong action name ') . $action);
        }

        $this->afterAction($action);
    }

    public function assign($var, $value = null) {
        if (is_array($var)) {
            $view = $this->getView();
            foreach ($var as $key => $curVar) {
                $view->assign($key, $curVar);
            }
        } else {
            $this->getView()->assign($var, $value);
        }
    }

    function getFrame() {
        return Vulkan::getConfig('dir.frontend') . 'Views\\' . ($this->frame ?? Vulkan::$app->frame);
    }

    function getViewPath() {
        return Vulkan::getConfig('dir.frontend') . 'Views\\';
    }

    private $_handlers = array();

    public function init() {

        if (self::isAjaxRequest()) {
            $data = $_POST;
            $action = $data['action'];

            if ($this->isActionProcessable($action)) {
                $method = "_ajax" . ucfirst($action);
                if (method_exists($this, $method) || isset($this->_handlers[$action])) {
                    $obj = $this->_handlers[$action] ?? $this;
                    $this->sendAjax(
                        call_user_func_array([$obj, $method], [$data['data']])
                    );
                }

            }
            die();
        }

    }

    function registerAjaxHandler($handlerClass) {
        foreach ($handlerClass::getHandlers() as $method) {
            if (!$this->isActionProcessable($method)) {
                $this->_handlers[$method] = $handlerClass;
            } else {
                //если это уже зареганный метод, того же самого класса, просто нужно пропустить
                if (isset($this->_handlers[$method]) && $this->_handlers[$method] == $handlerClass) {
                    continue;
                } else {
                    throw new Exception("Only one handler is possible on the action. Action: " . $method);
                }
            }
        }

        foreach ($handlerClass::getRegisteredAjaxClasses() as $class) {
            $this->registerAjaxHandler($class);
        }

    }

    function isActionProcessable($action) {
        $method = "_ajax" . ucfirst($action);
        if (method_exists($this, $method)) {
            return true;
        } elseif (in_array($action, array_keys($this->_handlers))) {
            return true;
        }

        return false;
    }

    static function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") || isset($_REQUEST['is_ajax']) && (int)$_REQUEST['is_ajax'];
    }

    private function sendAjax($response) {
        header('content-type: application/json; charset=utf-8');
    }

}