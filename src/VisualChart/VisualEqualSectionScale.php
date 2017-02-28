<?php
/**
 * Equal scale
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package VisualChart
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\VisualChart;

use vulkan\Core\EqualScale;

class VisualEqualSectionScale extends EqualScale
{
    const DEFAULT_CSS_CLASS = 'vul-scale';

    use SectionScaleVisualEngine;
    use CommonVisualParamsTrait;

    /* @var $_chart \app\vulkan\Visual\VisualChart */
    public $_chart;

}