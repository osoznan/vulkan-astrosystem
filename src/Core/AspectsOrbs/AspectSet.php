<?php
/**
 * Base class for a single aspect/orb table
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @subpackage Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\Core\C;
use vulkan\Core\Vulkan;
use vulkan\System\ICrudAdapter;
use vulkan\System\Transfer;

class AspectSet/* implements ICrudAdapter*/ {

    public $active;

    public $orbs;

    public $title;
    public $description;

    public $coef = 1.0;

    public $columnCount;

    public function __construct($params = null) {
        Config::settings($this, $params);
    }

    public function setActive($list, $isActive = true) {

    }

    function orbIds($points) {}

    function aspectFromDegree($degree) {
        //var_dump( $this->aspectSet->data[AspectInfo::aliasToId(intval($degree))] ); die();
        return $this->data[AspectInfo::aliasToId(intval($degree))];
    }

    function getAspectOrbs($aspectId) {
        if(isset($this->orbs[$aspectId])) {
            return $this->orbs[$aspectId]->orbs;
        }
    }

    function toArrayMinimized() {
        return [
            'orbs' => $this->orbs,
            'coef' => $this->coef,
            'columnCount' => $this->columnCount
        ];
    }

}