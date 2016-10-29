<?php
/**
 * Base class for basic essential dignities (rule, co-rule, exaltation, exile, co-exile, fall)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @subpackage Essentiality
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\Essential;

use vulkan\Core\SignScale;

class EssDignity {

    const COMMON = 0;
    const RULE_NIGHT = 1;
    const RULE_SHESTOPALOV = 2;

    static public $classes = [
        self::COMMON => '\vulkan\Core\Essential\EssDignity',
        self::RULE_NIGHT => '\vulkan\Core\Essential\NightRuleEssential',
        self::RULE_SHESTOPALOV => '\vulkan\Core\Essential\ShestopalovEssential'
    ];

    const RULE = 0;
    const CORULE = 1;
    const EXALT = 2;
    const EXILE = 3;
    const COEXILE = 4;
    const FALL = 5;

    const DIGNITY_NAMES = [
        self::RULE => 'rule', self::CORULE => 'co-rule', self::EXALT => 'exaltation',
        self::EXILE => 'exile', self::COEXILE => 'co-exile', self::FALL => 'fall',
    ];

    static public $type = self::COMMON;

    /* @var array $signData */
    public $signData;
    /* @var array */
    public $pointData;

    public $adapter;

    static function getInstance($name) {
        $data = (new JsonAdapter())->load($name);
        $instance = new static::$classes[$data['type']];
        $instance->signData = $data['data'];
        return $instance;
    }

    function getSignDignity($sign, $dignity) {
        $this->signData[$sign][$dignity];
    }

    function getRuler($sign) {
        return $this->signData[$sign][0];
    }

    function getCoRuler($sign, $params = null) {
        return $this->signData[$sign][1];
    }

    function getExaltor($sign) {
        return $this->signData[$sign][2];
    }

    function getExiler($sign) {
        return $this->signData[$sign][3];
    }

    function getCoExiler($sign) {
        return $this->signData[$sign][4];
    }

    function getFaller($sign) {
        return $this->signData[$sign][5];
    }

    function isInDignity($point, $dignity) {
        return $adapter->isInDignity($point, $dignity);
        //if ($this->signData[$point->getSign()][$dignity] == $point->info->id)
    }

    function getPointDignity($pointId, $sign) {
        for ($dign = 0; $dign < 5; $dign++) {
            if ($pointId === $this->signData[$sign][$dign]) {
                return $dign;
            }
        }
    }

    function rules() {

    }

}