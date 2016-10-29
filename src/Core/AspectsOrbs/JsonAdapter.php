<?php
/**
 * Adapter for loading the aspect/orb table which is stored in json format
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @subpackage Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\System\Vulkan;

class JsonAdapter {

    public $data;

    static function create($data) {

    }

    function load($name) {
        $range = function($start, $stop) {
            $arr = [];
            for ($i = $start; $i <= $stop; $i++) {
                $arr[] = $i;
            }
            return $arr;
        };

        $this->data = json_decode(file_get_contents(Vulkan::getConfig('dir.config') . '/AspectSets/' . $name . '.json'), 1);
        $aspects = [];
        foreach ($this->data['orbs'] as $key => &$elem) {
            foreach ($elem as $_ => $orb) {
                $elem[$_] = degRad($orb);
            }
            $aspects[] = new AspectData($key, $elem);
        }
        foreach ($this->data['orbCols'] as $key => &$elem) {
            if (is_string($elem)) {
                $elem = $range((int)substr($elem, 0, 2), (int)substr($elem, 3));
            }
        }
        return [
            'orbs' => $this->data['orbs'],
            'orbCols' => $this->data['orbCols'],
            'aspectsActive' => $this->data['aspectsActive']
        ];
    }

    static function update($data) {

    }

    static function delete($data) {

    }

    static function setOrbIds($data) {

    }

}