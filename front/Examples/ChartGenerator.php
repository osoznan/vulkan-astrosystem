<?php
/**
 * Examples with VisualChart
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Examples
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace vulkan\front\Examples;

use vulkan\Core\AspectsOrbs\AspectManager;
use vulkan\Core\ChartSection;
use vulkan\VisualChart\VisualChart;
use vulkan\VisualChart\VisualHouseSectionScale;

class ChartGenerator {

    public $chart;
    public $rSectionCount;
    public $rSectionScaleCount;

    public static function randBool() {
        return rand(0, 1) ? true : false;
    }

    public static function randItem(...$array) {
        return $array[rand(0, count($array) - 1)];
    }

    public static function randDate() {
        return rand(1800, 2020) . '-' . sprintf("%02d", rand(1, 12)) . '-' . sprintf("%02d", rand(1, 28))
        . ' ' . sprintf("%02d", rand(0, 23)) . ':' . sprintf("%02d", rand(0, 59)) . ':00';
    }

    public static function randLocation() {
        return [rand(-180, 180), rand(-65, 65)];
    }

    protected function randSection($key) {
        $randScales = [];
        for ($i = 0; $i < rand(0, $this->rSectionScaleCount); $i++) {
           $randScales[] = $this->randScale();
        }
        $width = rand(30, 60);
        return [
            'class' => '\vulkan\VisualChart\VisualChartSection',
            'key' => $key,
            'info' => [
                'class' => '\vulkan\Core\ChartSectionInfo',
                'dateTime' => $this::randDate(),
                'gmt' => rand(-11, 11),
                'location' => $this::randLocation()
            ],
            'width' => $width,
            'points' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],

            'scales' => $randScales,

            'pointFontSize' => rand(12, $width / 2),
            'showPointInfo' => $this->randBool(),
            'houses' => \vulkan\Core\ChartSection::createHouses()
        ];
    }

    protected function randSystemSection() {
        return [
            'class' => '\vulkan\VisualChart\VisualChartSection',
            'width' => rand(20, 70),
            'key' => 'system',
            'scales' => [
                [
                    'class' => '\vulkan\VisualChart\VisualAstroSignScale',
                    'clip' => [0, rand(95, 100) / 100],
                    'sectorCount' => 12,
                    'majorStep' => $this->randItem(3, 4)
                ]
            ]
        ];
    }

    protected function randScale() {
        $class = $this::randItem(
            '\vulkan\VisualChart\VisualEqualSectionScale', '\vulkan\VisualChart\VisualHouseSectionScale'
        );
        if (strpos($class, 'House')) {
            $clip = [rand(0, 0.1), rand(95, 100) / 100];
        } else {
            $clip = [0, rand(7, 16) / 100];
        }
        return [
            'class' => $class,
            'clip' => $clip,
            'sectorCount' => $this->randItem(12, 12, 12*3, 12*6, 12*10),
            'majorStep' => $this->randItem(3, 6, 12)
        ];
    }

    public function randChart($sectionCount, $sectionScaleCount) {
        $this->rSectionScaleCount = $sectionScaleCount;
        $randSections = [];
        for ($i = 0; $i < rand(1, $sectionCount); $i++) {
            $randSections[] = $this->randSection('section-' . $i);
        }
        return [
            'innerRadius' => rand(0, 150) + 50,
            'direction' => $this->randItem(VisualChart::DIRECTION_ANTICLOCKWISE, VisualChart::DIRECTION_CLOCKWISE),
            'startPosition' => $this->randItem(VisualChart::START_FROM_ARIES, VisualChart::START_FROM_ASCENDANT),

            'sections' => array_merge($randSections, [$this->randSystemSection()]),

            'globalHouses' => [
                'attachedSection' => 'section-0',
                'stickOutDistance' => rand(5, 20)
            ],

            'aspectManager' => [
                'aspectedSections' => [
                    ['key' => 'section-0', 'aspectation' => $this->randItem([AspectManager::ASPECTATION_POINTS, AspectManager::ASPECTATION_HOUSES])],
                    ['key' => 'section-0', 'aspectation' => $this->randItem([AspectManager::ASPECTATION_POINTS, AspectManager::ASPECTATION_HOUSES])]
                ]
            ],
        ];
    }

    /**
     * A typical single-section chart wheel
     * @return VisualChart
     */
    public static function generalVisualChart() {
        return new VisualChart([
            'innerRadius' => 105,
            'key' => 'example-chart-1',
            'direction' => VisualChart::DIRECTION_ANTICLOCKWISE,
            'startPosition' => VisualChart::START_FROM_ARIES,
            'sections' => [
                [
                    'class' => '\vulkan\VisualChart\VisualChartSection',
                    'key' => 'section-1',
                    'width' => 65,
                    'info' => [
                        'class' => '\vulkan\Core\ChartSectionInfo',
                        'dateTime' => static::randDate(),
                        'gmt' => 3,
                        'location' => static::randLocation()
                    ],
                    'points' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    'houses' => \vulkan\Core\ChartSection::createHouses(),
                    'showPointInfo' => false
                ],
                [
                    'class' => '\vulkan\VisualChart\VisualChartSection',
                    'width' => 30,
                    'key' => 'system',
                    'cssClass' => 'system-1',
                    'scales' => [
                        [
                            'class' => '\vulkan\VisualChart\VisualAstroSignScale',
                            'clip' => [0, 0.95],
                            'sectorCount' => 12,
                            'majorStep' => 3
                        ],
                        [
                            'class' => '\vulkan\VisualChart\VisualEqualSectionScale',
                            'clip' => [0, -0.2],
                            'sectorCount' => 72,
                            'majorStep' => 2
                        ]
                    ],
                ],
            ],
            'globalHouses' => [
                'attachedSection' => 'section-1'
            ],
            'aspectManager' => [
                'aspectedSections' => [
                    ['key' => 'section-1', 'aspectation' => AspectManager::ASPECTATION_POINTS],
                    ['key' => 'section-1', 'aspectation' => AspectManager::ASPECTATION_POINTS]
                ]
            ],
        ]);
    }

}