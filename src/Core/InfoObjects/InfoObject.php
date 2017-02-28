<?php
/**
 * Base class for storing info in basic Vulkan objects (point, aspect, house, sign and anything you wish)
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\InfoObjects
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

use vulkan\System\Transfer;

class InfoObject {

    /** @var integer */ 
    public $id;
    /** @var string Name, i.e. Libra, Asc, Saturn */
    public $name;
    /** @var string Alias, i.e. sag (Sagittarius), plu (Pluto) */
    public $alias;
    /** @var string Caption, as usual a symbol which denotes an object */
    public $caption;

    public function __construct($id, $name, $alias = null) {
        $this->id = $id;
        $this->name = $name;
        $this->alias = $alias;
    }

    public function __toString() {
        return "$this->name ($this->alias, $this->caption)";
    }

    public function toArray() {
        return Transfer::serializeObjectVars($this, 'id');
    }
}