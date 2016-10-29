<?php
/**
 * Class of the most common chart with a single section.
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\front\Components\GeneralSingleChart;

use \vulkan\Components\VisualChart\{
    VisualChartSection, VisualEqualSectionScale, GlobalHouseSectionScale, VisualAstroSignScale
};
use vulkan\Components\VisualChart\VisualChart;
use \vulkan\Core\ChartSection;
use vulkan\System\Vulkan;

class GeneralSingleChart extends \vulkan\Components\VisualChart\VisualChart
{
    /* @var $section \vulkan\Core\ChartSection */
    public $section;
    /* @var $section \vulkan\Core\ChartSection */
    public $systemSection;

    public $globalHousesScale;

    /* @var array $points \vulkan\Core\AstroPoint */
    public $points;

    /* @var array $houses \vulkan\Core\House */
    public $houses;

    public function __construct($params = null) {
        parent::__construct($params);
        $this->init();
    }

    public function init() {
        $this->innerRadius($this->innerRadius ?? 100)
            ->startPosition($this->startPosition ?? VisualChart::START_FROM_ARIES)
            ->direction($this->direction ?? VisualChart::DIRECTION_ANTICLOCKWISE);

        $this->addGeneralSection()
            ->systemSection();

        $this->aspectManager([
            $this->aspectSet ?? 'aspectSet' => ['class' => Vulkan::getConfig('aspectSet.class')]
        ])->aspectedPoints($this->section->points, $this->section->points);
        $this->points = $this->section->points;
        $this->houses = $this->section->houses;

    }

    public function addGeneralSection($params = null) {
        $this->addSection($this->section = (new VisualChartSection())
            ->addScale((new VisualEqualSectionScale(36))
                ->clip([0, -0.1]))
            ->width($this->sectionWidth ?? 50)
            ->addPoints(ChartSection::createPoints(...[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]))
            ->addHouses(ChartSection::createAllHouses())
            ->pointFontSize(22)
            ->showPointInfo(false), 'default');

        return $this;
    }

    public function systemSection() {
        $this->addSection($this->systemSection = (new VisualChartSection)
            ->addScale((new VisualAstroSignScale)
                ->clip([0.15, 0.85])->majorStep(3))
            ->addScale((new VisualEqualSectionScale(72))
                ->clip([0, 0.15]))
            ->addScale($this->globalHousesScale = (new GlobalHouseSectionScale())->attachedSection('default')
                ->stickOutDistance($this->stickOutDistance ?? 44))
            ->width($this->systemSectionWidth ?? 50)
            ->cssClass('vul-system-section'), 'system');
    }

}

