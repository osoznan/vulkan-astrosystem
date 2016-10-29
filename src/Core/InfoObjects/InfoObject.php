<?php
/**
 * Base class for storing info in some Vulkan objects (point, aspect, house, sign)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

use vulkan\System\Transfer;

class InfoObject {

    public $id;
    public $name;
    public $alias;

    public static $aliasToId = [];

    public function __construct($id, $name, $alias = null) {
        $this->id = $id;
        $this->name = $name;
        $this->alias = $alias;

        if ($alias !== null) {
            static::$aliasToId[$alias] = $id;
        }
    }

    public static function aliasToId($alias) {
        if (isset(static::$aliasToId[$alias])) {
            return static::$aliasToId[$alias];
        }
    }

    public function toArray() {
        return Transfer::serializeObjectVars($this, 'id');
    }
}