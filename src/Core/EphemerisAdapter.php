<?php
/**
 * Ephemeris adapter abstract class.
 * Derive from it to specify all of your necessary ephemeris calculations.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

abstract class EphemerisAdapter {

    public $chartSectionInfo;
    public $settings;

    function __construct($data = null) {}

    abstract function init();

    /**
     * Calculates the ephemeris data for a section (which denotes any event, i.e. birth, event etc.)
     * The "main" ephemeris calculation, receiving longitude and some basic info about points and houses
     * on a given moment && location
     * @param array $params Additional parameters if any
     * @return mixed
     */
    abstract function calculate($params);

}