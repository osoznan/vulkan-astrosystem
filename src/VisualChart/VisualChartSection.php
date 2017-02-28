<?php
/**
 * Class for a section display
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\VisualChart;

use vulkan\Core\ChartSection;
use vulkan\Core\ChartSectionInfo;
use vulkan\System\Config;
use vulkan\System\Transfer;


class VisualChartSection extends ChartSection
{
    const DEFAULT_CSS_CLASS = 'vul-section';

    use ChartSectionVisualEngine;
    use CommonVisualParamsTrait;

}