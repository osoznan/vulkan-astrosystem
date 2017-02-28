<?php
/**
 * Typical examples
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Examples
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\front\Examples;

use vulkan\Core\AspectsOrbs\AspectManager;
use vulkan\System\C;
use vulkan\Core\Chart;
use vulkan\System\Vulkan;
use vulkan\Core\ChartSection;
use vulkan\Core\Essentiality\EssDignity;
use vulkan\VisualChart\VisualChart;

class Tutorial {

    public static function initChart() {
        Vulkan::initialize();

        $chart = (new Chart([
            'sections' => [
                [
                    // a class denoting the chart section
                    'class' => '\\vulkan\\Core\\ChartSection',

                    // unique key for reference
                    'key' => 'section-1',

                    // input info which any normal chart has
                    'info' => [
                        'class' => '\\vulkan\\Core\\ChartSectionInfo',
                        'dateTime' => '1912-01-02 12:00:00',
                        'gmt' => '03:00',

                        // longitude and latitude
                        'location' => [10.5, 22.11]
                    ],

                    // points with ids 0, 1, ..., 10 (SUN...PLUTO + North Node)
                    'points' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],

                    // create houses
                    'houses' => ChartSection::createHouses(),

                    // houseSystem is defined by its alias (default is Koch), let's set Placidus
                    'houseSystem' => 'P'
                ]
            ]
        ]))->run();

        $section = $chart->getSection("section-1");

        // the Sun object (the first indexed point)
        $sun = $section->points[0];

        // the Sun position in radians
        $sunPos = $section->points[0]->position;

        // it\'s clear without any comment:
        $skyObjects = $section->points;

        $moonHouseNum = $skyObjects[1]->houseId;
        $moonSignNum = $skyObjects[1]->signId;

        $plutoPosition = $skyObjects[9]->position; // in radians
        $plutoPositionDegrees = $skyObjects[9]->getDegree();

        $distanceFromMoonToSunDegrees = C::radDeg($skyObjects[1]->distanceFrom($sun));

        $symbolOfTheSun = $sun->info->caption;

        // something about houses
        $houses = $section->houses;

        $ascendant = $houses[0];
        $ascPosition = $ascendant->position;

        $houseOrdinalNum = $ascendant->info->id; // = 0
    }

    public static function aspectsAndOrbs() {
        Vulkan::initialize();

        $chart = (new VisualChart([
            'innerRadius' => 120,

            'direction' => VisualChart::DIRECTION_ANTICLOCKWISE,
            'startPosition' => VisualChart::START_FROM_ARIES,

            "sections" => [
                [
                    "class" => "\\vulkan\\VisualChart\\VisualChartSection",

                    // section ID
                    'key' => 'section-1',

                    "info" => [
                        "class" => "\\vulkan\\Core\\ChartSectionInfo",
                        'dateTime' => '1980-01-01 20-00-00'
                    ],

                    // let\'s add planets from the Sun (key = 0) to Mars (key = 4) and North Node (key=10)
                    "points" => [0, 1, 2, 3, 4, 10],
                    'houses' => ChartSection::createHouses(),

                    // Here come the visual properties of the section
                    'width' => 60,
                ],
                [
                    "class" => "\\vulkan\\VisualChart\\VisualChartSection",
                    'key' => 'system',
                    'width' => 40,
                    'scales' => [
                        [
                            "class" => '\\vulkan\\VisualChart\\VisualAstroSignScale',
                            'clip' => [0, 1],
                        ]
                    ]
                ],
            ],

            'globalHouses' => [
                'attachedSection' => 'section-1'
            ],

            // here we invoke the default aspects & orbs properties (just setting aspectation points)
            "aspectManager" => [
                // aspectation properties: what section to aspect with each other
                // and what elements to aspect (points, houses, points & houses)
                // here we find aspects between points of our section-1:
                "aspectedSections" => [
                    ["key" => "section-1", "aspectation" => AspectManager::ASPECTATION_POINTS],
                    ["key" => "section-1", "aspectation" => AspectManager::ASPECTATION_HOUSES]
                ]
            ],
        ]))->calculate();

        // calculate ephemeris positions and aspects
        echo $chart->run(500, 500);
        echo 'Aspect list:<p>';

        // run through all found aspects and ouput the aspect info
        foreach ($chart->aspectManager->aspectList as $aspect) {
                echo $aspect->point1->info->caption . '-' . $aspect->point2->info->caption . ': '
                . C::$aspectInfos[$aspect->info->id]->name . ', ' . C::radDeg($aspect->distance()) . 'deg<br>';
        }
    }

    public static function essentialDignities() {
        Vulkan::initialize();

        // Load essential dignities.
        // If called without arguments, the default data is loaded (from front\Data\Essentiality\default.json)
        $ess = EssDignity::getInstance();
        $ess = EssDignity::getInstance();

        // Ruler name of Taurus
        echo C::$pointInfos[$ess->getRuler(C::TAURUS)]->name; // Venus

        // Exaltor name of Capricornus
        echo C::$pointInfos[$ess->getExaltor(C::CAPRICORN)]->name; // Mars

        // Faller name of Libra
        echo C::$pointInfos[$ess->getFaller(C::LIBRA)]->name; // Sun

        // it's more interesting
        $chart = ChartGenerator::generalVisualChart()->calculate();

        //Say we have $chart which has a single section with key "section-1". So,
        $points = $chart->getSection("section-1")->points;

        // ruler of the Sign where the Moon is located
        echo $ess->getRuler($points[C::MOON]->signId);

        // whether Mercury rules the Sign it\'s located within?
        if ($ess->isInDignity($points[C::MERCURY], EssDignity::RULE)) {
            // Mercury is in its Sign
        } else {
            // Mercury doesn\"t rule there
        }

        // Write the dignity name of each planet if any
        foreach ($points as $point) {
            $dignity = $ess->getPointDignityInSign($point->info->id, $point->signId);
            if ($dignity) {
                echo $point->info->name . " is in " . C::$signInfos[$point->signId]->name
                    . " and has dignity: " . EssDignity::DIGNITY_NAMES[$dignity] . "<br>";
            }
        }

    }
}