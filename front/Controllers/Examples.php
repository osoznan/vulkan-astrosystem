<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2016
 * Time: 12:24
 */

namespace vulkan\front\Controllers;

use vulkan\Components\VisualChart;
use vulkan\Core\AstroMoment;
use vulkan\Core\C;
use vulkan\Core\Chart;
use vulkan\Core\ChartSection;
use vulkan\Core\ChartSectionInfo;
use vulkan\Core\Essential\EssDignity;
use vulkan\Core\Location;
use vulkan\Core\Widget;
use vulkan\front\Components\Accentuation;
use vulkan\front\Components\AspectListCrossTable;
use vulkan\front\Components\AspectListTable;
use vulkan\front\Components\ChartDataInput;
use vulkan\front\Components\GeneralPointInfo;
use vulkan\Helpers\Fmt;
use vulkan\Core\Vulkan;
use vulkan\System\Config;
use vulkan\front\Components\GeneralSingleChart;

class Examples extends Controller {

    public $frame = 'frame_examples';

    public function actionWidgets() {

         /* @var $chart \vulkan\Components\VisualChart\GeneralSingleChart */

        $chart = $this->getMultiChart();

        $this->chartTitle = ucfirst($chart->title);
        $this->eventData = date('d-M-y H:i:s', $info->moment->dateTime) . ' | '
            . Location::floatToLongitude($info->location->longitude) . ' '
            . Location::floatToLatitude($info->location->latitude)
            . ' | ' . $info->location->name;

        foreach ($chart->getPointSections() as $key => $section) {
            if (\Yii::$app->session->get('chart-' . $key)) {
                $section->info = json_decode(\Yii::$app->session->get('chart-' . $key));
            } else {
                $section->info = new ChartSectionInfo();
                $section->info->moment = new AstroMoment(mktime());
            }
        }

        $input = new ChartDataInput([
            'chart' => $chart,
            'hidden' => ['ctrl' => 'examples', 'action' => 'widgets']
        ]);

        if ($_GET['section']) {
            $info = $chart->getSection($_GET['section'])->info = $input->getChartData();
            $_SESSION['chart-' . $_GET['section']] = json_encode($info);
        }

        $this->getView()->chartDataInput = $input->run();


        $this->assign([
            'chartBlock' => (new VisualChart([
                'chart' => $chart,
                'width' => 750,
                'height' => 750
            ]))->run(),
            'analysesBlock' => (new GeneralPointInfo([
                    'chart' => $chart,
                    'sections' => $chart->getPointSections(),
                    'title' => 'General'
                ]))->run() .
                (new Accentuation([
                    'sections' => $chart->getPointSections(),
                    'title' => 'Accentuation'
                ]))->run() .
                (new AspectListTable([
                    'chart' => $chart,
                    'title' => 'Aspect List'
                ]))->run() .
                (new \vulkan\front\Components\AspectListCrossTable([
                    'chart' => $chart,
                    'title' => 'Aspect Cross Table'
                ]))->run() .
                (new \vulkan\front\Components\GeneralEssDignity([
                    'sections' => $chart->getPointSections(),
                    'title' => 'Essential Dignity'
                ]))->run(),
            'chart' => $chart,
            'chartTitle' => $this->chartTitle,
            'eventData' => $this->eventData
        ]);

        return $this->render('widgets');
    }

    function getMultiChart() {

        return new VisualChart\VisualChart($data = [
            'title' => 'A Multisection Chart Example',
            'innerRadius' => 135,
            'key' => 'example-chart-1',
            'direction' => VisualChart\VisualChart::DIRECTION_ANTICLOCKWISE,
            'startPosition' => VisualChart\VisualChart::START_FROM_ARIES,
            'sections' => [
                [
                    'class' => '\vulkan\Components\VisualChart\VisualChartSection',
                    'key' => 'section-1',
                    'title' => 'Section The Inner',
                    'info' => [
                        'class' => '\vulkan\Core\ChartSectionInfo',
                        'dateTime' => "1981-05-11 12:00:00",
                        'gmt' => "03:00"
                    ],
                    'width' => 40,
                    'points' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    'scales' => [
                        [
                            'class' => '\vulkan\Components\VisualChart\VisualEqualSectionScale',
                            'clip' => [0, -0.18],
                            'sectorCount' => 12*3,
                            'majorStep' => 3
                        ],
                    ],
                    'pointFontSize' => 18,
                    'showPointInfo' => false,
                    'houses' => []
                ],
                [
                    'class' => '\vulkan\Components\VisualChart\VisualChartSection',
                    'key' => 'section-2',
                    'title' => 'Section The Middle',
                    'info' => [
                        'class' => '\vulkan\Core\ChartSectionInfo',
                        'dateTime' => "1920-01-19 18:15:00",
                        'gmt' => "03:00"
                    ],
                    'scales' => [
                        [
                            'class' => '\vulkan\Components\VisualChart\VisualEqualSectionScale',
                            'clip' => [0, 0.95],
                            'sectorCount' => 12,
                            'majorStep' => 3
                        ],
                        [
                            'class' => '\vulkan\Components\VisualChart\VisualHouseSectionScale',
                            'clip' => [0, 1],
                            'majorStep' => 3
                        ],
                    ],
                    'width' => 40,
                    'pointFontSize' => 18,
                    'points' => [0, 1, 2, 3, 4, 5, 6],
                    'houses' => []
                ],
                [
                    'class' => '\vulkan\Components\VisualChart\VisualChartSection',
                    'key' => 'section-3',
                    'title' => 'Section The Outer',
                    'width' => 65,
                    'points' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    'houses' => []
                ],
                [
                    'class' => '\vulkan\Components\VisualChart\VisualChartSection',
                    'width' => 30,
                    'key' => 'system',
                    'cssClass' => 'system-1',
                    'scales' => [
                        [
                            'class' => '\vulkan\Components\VisualChart\VisualAstroSignScale',
                            'clip' => [0, 0.95],
                            'sectorCount' => 12,
                            'majorStep' => 3
                        ],
                        [
                            'class' => '\vulkan\Components\VisualChart\VisualEqualSectionScale',
                            'clip' => [1, 1.2],
                            'sectorCount' => 72,
                            'majorStep' => 2
                        ],
                        [
                            'class' => '\vulkan\Components\VisualChart\VisualEqualSectionScale',
                            'clip' => [2.1, 2.4],
                            'sectorCount' => 360,
                            'majorStep' => 18
                        ],
                        [
                            'class' => '\vulkan\Components\VisualChart\GlobalHouseSectionScale',
                            'attachedSection' => 'section-3',
                            'stickOutDistance' => 40,
                            'pointFontSize' => 17
                        ]
                    ]
                ]
            ],
            'aspectManager' => [
                'class' => 'vulkan\Core\AspectsOrbs\AspectManager',
                'orbCalcType' => 2,
                'aspectSet' => [
                    'class' => 'vulkan\Core\AspectsOrbs\GeneralAspectSet'
                ],
                'aspectedSections' => [
                    'keys' => ['section-1', 'section-3'],
                ]
            ],
        ]);
    }

    public function actionJsonchart() {

        /* @var $chart \vulkan\Components\VisualChart\GeneralSingleChart */

        $jsonCode = file_get_contents(__DIR__ . '/../Data/chart-json-examples/' . ($_GET['example'] ?? 'example-1') . '.json');

        $chart = new VisualChart\VisualChart(json_decode($jsonCode, 1));

        $this->chartTitle = ucfirst($chart->title);

        $this->assign([
            'chartBlock' => (new VisualChart([
                'chart' => $chart,
            ]))->run(),
            'text' => $jsonCode
        ]);

        return $this->render('jsonchart');
    }

    function actionTest() {
        error_reporting(E_ERROR || E_COMPILE_WARNING || E_WARNING || E_NOTICE || E_RECOVERABLE_ERROR);

        C::readBasicElements();

        try {
            //new \vulkan\TestCase\Core\ChartSection;
            new \vulkan\TestCase\Core\InfoObject;
            new \vulkan\TestCase\Core\SectionScale;
            new \vulkan\TestCase\Core\AstroCalc;
            new \vulkan\TestCase\Helpers\Fmt_;
            new \vulkan\TestCase\Core\BaseAstroPoint;
            new \vulkan\TestCase\Core\EqualSectionScale();
        } catch (\Exception $e) {
            echo 'error';
        } finally {

        }
    }

    public function actionSingle() {

        $this->assign([
            'chartBlock' => ((new GeneralSingleChart([
                'width' => 650,
                'height' => 650,
                'innerRadius' => 180,
                'startPosition' => VisualChart\VisualChart::START_FROM_ASCENDANT
            ]))->run()),
            'title' => 'Simple (Single-Section Chart)',
            'description' => 'This example is the most typical chart, consisting of a single point section. It may have
                different properties like width, color, size of astrosymbols etc. You can add one or more scales to a
                section, which has its minor and major divisions. There is a system section as well, where Sign symbols are painted.'
        ]);

        return $this->render('chart_example');
    }

    public function actionMulti() {
        $this->assign([
            'chartBlock' => (new VisualChart([
                'chart' => $this->getMultiChart(),
                'width' => 700,
                'height' => 700
            ]))->run(),
            'title' => 'MultiSection Chart',
            'description' => 'This example chart consists of three point sections. They may have
                different properties like width, color, size of astrosymbols etc. There is a
                system section as well, where Sign symbols are painted.<p>
                The "ring" around other content is a scale which is attached to the system section.
                Yes, it\'s possible to do such tricks.'
        ]);

        return $this->render('chart_example');
    }

}