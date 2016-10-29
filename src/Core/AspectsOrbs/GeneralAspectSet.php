<?php
/**
 * A common aspect/orb table
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @subpackage Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

/*
 * class denoting aspect set with orbs for each planet, orb columns for (nodes, lilith) and (parses)
 */

class GeneralAspectSet extends AspectSet {

    public $adapter;

    function __construct($params = null) {
        $this->adapter = new JsonAdapter();
        $this->load($params['name'] ?? 'general');
    }

    function orbData($param = null) {
        foreach ($this->aspects as $aspect) {
            $this->data[] = new AspectData($aspectId = $aspect->id, [$param[$aspectId]]);
        }
        return $this;
    }

    function orbIds($points) {
        foreach ($points as $point) {
            foreach ($this->orbCols as $key => $col) {
                if (in_array($point->info->id, $col)) {
                    $point->orbId = $key;
                    break;
                }

            }
        }
        return $this;
    }

    function load($name) {
        $data = $this->adapter->load($name);
        $this->orbs = $data['orbs'];
        $this->orbCols = $data['orbCols'];
        $this->columnCount = count($this->data['orbCols']);
        return $this;
    }

}