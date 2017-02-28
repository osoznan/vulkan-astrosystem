<?php
/**
 * Base class for basic essential dignities (rule, co-rule, exaltation, exile, co-exile, fall)
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Essentiality
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\Essentiality;

use vulkan\System\Vulkan;

class EssDignity {

    const COMMON = 0;
    const RULE_NIGHT = 1;
    const RULE_SHESTOPALOV = 2;

    static public $classes = [
        // used for double ruling or ñeptener
        self::COMMON => '\vulkan\Core\Essentiality\EssDignity',
        // used for double ruling at night only
        self::RULE_NIGHT => '\vulkan\Core\Essentiality\NightRuleEssential',
        // double rule if retrograde planet, to do in the future
        self::RULE_SHESTOPALOV => '\vulkan\Core\Essentiality\ShestopalovEssential'
    ];

    const RULE = 0;
    const RULE2 = 1;
    const EXALTATION = 2;
    const DETRIMENT = 3;
    const DETRIMENT2 = 4;
    const FALL = 5;

    const DIGNITY_NAMES = [
        self::RULE => 'rule', self::RULE2 => 'co-rule', self::EXALTATION => 'exaltation',
        self::DETRIMENT => 'exile', self::DETRIMENT2 => 'co-detriment', self::FALL => 'fall',
    ];

    /** @var EssDignity[] Cache for loaded items */
    protected static $_loadedItems;

    static public $type = self::COMMON;

    /* @var array $signData */
    public $signData;
    /* @var array */
    public $pointData;

    public $adapter;

    /** @return EssDignity */
    public static function getInstance($name = null) {
        $name = $name ? $name : Vulkan::getConfig('essDignity.file');
        static::$_loadedItems[$name] = null;
        if (!isset(static::$_loadedItems[$name])) {
            $result = (new JsonAdapter())->load($name);

            if (!$result) {
                return false;
            }
            $instance = new static::$classes[$result['type']];
            $instance->signData = $result['data'];
            static::$_loadedItems[$name] = $instance;
        }
        return static::$_loadedItems[$name];
    }

    /**
     * A safe get of a point which has a given dignity in a given sign
     * @param integer $sign
     * @param integer $dignity
     * @return integer|null
     */
    function getSignDignity($sign, $dignity) {
        if (isset($this->signData[$sign][$dignity])) {
            return $this->signData[$sign][$dignity];
        }
    }

    /**
     * Gets a ruler of a Sign
     * @param integer $sign
     * @return integer
     */
    function getRuler($sign) {
        return $this->signData[$sign][self::RULE];
    }

    /**
     * Gets a co-ruler of a Sign
     * @param integer $sign
     * @param null $params The additional info for calculating co-ruler
     * @return integer
     */
    function getRuler2($sign, $params = null) {
        return $this->signData[$sign][self::RULE2];
    }

    /**
     * Gets an exaltor for a sign
     * @param $sign
     * @return integer
     */
    function getExaltor($sign) {
        return $this->signData[$sign][self::EXALTATION];
    }

    /**
     * Gets an "exiler" for a sign
     * @param $sign
     * @return integer
     */
    function getDetrimenter($sign) {
        return $this->signData[$sign][self::DETRIMENT];
    }

    /**
     * Gets an "co-exiler" for a sign
     * @param $sign
     * @return integer
     */
    function getDetrimenter2($sign) {
        return $this->signData[$sign][self::DETRIMENT2];
    }

    /**
     * Gets a "faller" for a sign
     * @param $sign
     * @return integer
     */
    function getFaller($sign) {
        return $this->signData[$sign][self::FALL];
    }

    function isInDignity($point, $dignity) {
        return $this->signData[$point->signId][$dignity] == $point->info->id;
    }

    /**
     * Gets a point dignity in a given sign if any
     * @param integer $pointId
     * @param integer $signId
     * @return integer
     */
    function getPointDignityInSign($pointId, $signId) {
        for ($i = 0; $i <= 5; $i++) {
            if ($pointId === $this->signData[$signId][$i]) {
                return $i;
            }
        }
        return false;
    }

}