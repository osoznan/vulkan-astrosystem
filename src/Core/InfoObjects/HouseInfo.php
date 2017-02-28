<?php
/**
 * House info, necessary for storing each of defined houses
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\InfoObjects
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

class HouseInfo extends InfoObject {
    public $element;
    public $cross;

    public function __construct($id, $name, $alias, $caption) {
        parent::__construct($id, $name, $alias);
        $this->caption = $caption;
        $this->element = $id % 3;
        $this->cross = $id % 4;
    }

    public function __toString() {
        return (string)printf('House %s', $this->name);
    }
}