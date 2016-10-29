<?php
/**
 * Essential dignity with double rule at night only.
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @subpackage Essentiality
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\Essential;

class NightRuleEssential extends EssDignity {

    static public $type = self::RULE_NIGHT;

    public $angleBeforeNight;
    public $angleAfterNight;

    function isNightSun($sunPoint) {
        if ($sunPoint->getHouseId)
    }

    /** @param vulkan\Core\AstroPoint $sunPoint */
    function getCoRuler($sign, $params = null) {
        return $this->isNightSun($sunPoint) ? $this->signData[$sign][1] : -1;
    }

    function getCoExiler($sign) {
        return $this->isNightSun($sunPoint) ? $this->signData[$sign][4] : -1;
    }

}