<?php
/**
 * Essential dignity with double rule at night only.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Essentiality
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\Essentiality;

use vulkan\Core\AstroPoint;
use vulkan\Core\SignScale;

class NightRuleEssential extends EssDignity {

    static public $type = self::RULE_NIGHT;

    public $isNightSun;

    /**
     * @param $point AstroPoint The Sun point object must be set
     * since calculation depends on its house num
     */
    public function init($sunPoint) {
        $this->isNightSun = SignScale::isNorth($sunPoint->signId);
    }

    /** @inheritdoc */
    function getRuler2($sign) {
        return $this->isNightSun ? $this->signData[$sign][self::RULE2] : null;
    }

    /** @inheritdoc */
    function getDetrimenter2($sign) {
        return $this->isNightSun ? $this->signData[$sign][self::DETRIMENT2] : null;
    }

}