<?php
/**
 * Sign info, necessary for storing each of defined signs
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

class SignInfo extends InfoObject {
    public $caption;

    public $element;
    public $cross;

    public function __construct($id, $name, $alias, $caption) {
        parent::__construct($id, $name, $alias);
        $this->caption = $caption;
        $this->element = $id % 3;
        $this->cross = $id % 4;
    }

}

