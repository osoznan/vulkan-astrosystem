<?php
/**
 * Adapter for managing essential dignity data in json format
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @subpackage Essentiality
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\Essential;

use vulkan\System\Vulkan;

class JsonAdapter {

    public $data;

    static function create($data) {
    }

    function load($name) {
        $this->data = json_decode(file_get_contents(Vulkan::getConfig('dir.config') . '/Essentials/' . $name . '.json'), 1);
        return $this->data;
    }

    static function update($data) {

    }

    static function delete($data) {

    }

}