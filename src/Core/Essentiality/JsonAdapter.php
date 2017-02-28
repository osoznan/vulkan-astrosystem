<?php
/**
 * Adapter for managing essential dignity data in json format
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Essentiality
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\Essentiality;

use vulkan\System\Vulkan;

class JsonAdapter {

    public $data;

    function load($name) {
        if (!is_file($name)) {
            $filename = Vulkan::getConfig('dir.data') . 'Essentiality/' . $name . '.json';
        } else {
            $filename = $name;
        }

        if (!file_exists($filename)) {
            return false;
        }

        $this->data = json_decode(file_get_contents($filename), 1);
        return $this->data;
    }

}