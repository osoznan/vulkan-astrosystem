<?php
/**
 * A common aspect/orb table
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\Core\AstroPoint;
use vulkan\System\C;
use vulkan\Helpers\Strings;
use vulkan\System\Vulkan;

class GeneralAspectSet extends AspectSet {

    public $adapter;

    protected static $_loadedItems;

    function __construct($params = null) {
        $this->adapter = new JsonAdapter();
        isset($params['file']) ? $this->load($params['file']) : $this->load(Vulkan::getConfig('aspectSet.file'));
    }

    /**
     * Assigns orbId of each point to its corresponding orb column
     * @param $points
     * @return $this
     */
    function orbIds($points) {
        $maxCol = count(reset($this->orbs)) - 1;

        parent::_setOrbIdsAsDefault($points);
        foreach ($points as $point) {
            foreach ($this->orbCols as $idx => $colIds) {
                if (in_array($point->info->id, $colIds)) {
                    if (is_numeric($colIds[0]) && $point->getKind() == AstroPoint::TYPE_POINT) {
                        $point->orbId = $idx;
                    } elseif ($colIds[0] == 'h' && $point->getKind() == AstroPoint::TYPE_HOUSE) {
                        //echo $point->info->caption . ' - ' . $idx . ' ';
                        $point->orbId = $idx;
                    }
                }
            }

            if ($point->orbId == AspectSet::DEFAULT_ORB_INDEX) {
                $point->orbId =  $maxCol;
            }
        }
        return $this;
    }

    /**
     * Loads AspectSet data from file or array and cache it in $_loadedItems
     * @param string $param Filename of AspectSet data or AspectSet data as array
     * @return $this|bool If success, zero is returned
     */
    function load($param) {
        if (!is_array($param)) {
            if (!isset(static::$_loadedItems[$param])) {
                static::$_loadedItems[$param] = $data = $this->adapter->load($param);
            } else {
                $data = static::$_loadedItems[$param];
            }
        } else {
            $data = $this->adapter->loadFromArray($param);
        }



        if (!$data || !$data['orbs'] || !$data['orbCols']) {
            return false;
        }

        $this->orbs = $data['orbs'];
        $this->orbCols = $data['orbCols'];

        $this->aspectsActive = C::checkParam($data['aspectsActive'], array_keys($data['orbCols']));

        $this->title = C::checkParam($data['title'], '');
        $this->description = C::checkParam($data['description'], '');
        return $this;
    }

}