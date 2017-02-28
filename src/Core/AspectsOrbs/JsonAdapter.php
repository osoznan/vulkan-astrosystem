<?php
/**
 * Adapter for loading the aspect/orb table which is stored in json format
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\System\C;
use vulkan\System\Vulkan;

class JsonAdapter {

    public $data;

    public function loadFromArray($data) {
        $range = function($start, $stop) {
            $arr = [];
            for ($i = $start; $i <= $stop; $i++) {
                $arr[] = $i;
            }
            return $arr;
        };

        foreach ($data['orbs'] as $aspectId => &$elem) {
            foreach ($elem as $key => $orb) {
                $elem[$key] = C::degRad($orb);
            }
        }

        foreach ($data['orbCols'] as $orbId => &$elem) {
            if (is_string($elem)) {
                $sep = strpos($elem, '-');
                $data['orbCols'][$orbId] = $range((int)substr($elem, 0, $sep), (int)substr($elem, $sep + 1));
            }
        }

        return $data;
    }

    public function load($name) {
        if (!is_file($name)) {
            $filename = Vulkan::getConfig('dir.data') . 'AspectSets/' . $name . '.json';
        } else {
            $filename = $name;
        }

        if (!file_exists($filename)) {
            return false;
        }

        $data = json_decode(file_get_contents($filename), 1);
        if ($data) {
            $this->data = $this->loadFromArray($data);
            return $this->data;
        }
    }

}