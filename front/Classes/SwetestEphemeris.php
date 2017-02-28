<?php
/**
 * Ephemeris adapter which uses Swetest, based on the Swiss Ephemeris
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Front\Classes
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace vulkan\front\Classes;

use vulkan\System\C;
use vulkan\Core\ChartSection;

class SwetestEphemeris extends \vulkan\Core\EphemerisAdapter {

    /** @var \vulkan\Core\ChartSection */
    public $section;

    public function __construct() {
    }

    public function init() {
        //there is no global preparations
    }

    /**
     * Calculates ephemeris data for a section
     * @param ChartSection $section
     */
    public function calculate($section) {
        $info = $section->info;
        error_reporting(E_COMPILE_ERROR);

        $dateTime = $info->moment->dateTime - $info->moment->gmt * 3600;
        $date = date("d.m.Y", $dateTime);
        $time = date('H:i:s', $dateTime);
        $section->houseSystem = C::checkParam($section->houseSystem, 'K');

        chdir(__DIR__ . "/../Swetest");

        exec ((PHP_OS == 'Linux' ? './' : '') .
            $a="swetest -edir./Sweph -b{$date} -ut{$time} -p0123456789mDttt -eswe -house{$info->location->longitude},{$info->location->latitude},{$section->houseSystem} -fldsj -g, -head", $out);

        //print_r($a); die();

        $longitude = $speed = [];
        foreach ($out as $key => $line) {
            $row = explode(',',$line);
            $longitude[$key] = $row[0];
            $speed[$key] = isset($row[2]) ? $row[2] : 0;
        }

        for ($i = 0; $i < count($section->points); $i++) {
            $section->points[$i]->position = C::degRad($longitude[$i]);
            $section->points[$i]->speed = C::degRad($speed[$i]);
        }

        if (!empty($section->houses)) {
            for ($i = 15; $i <= 26; $i++) {
                $section->houses[$i - 15]->position = C::degRad($longitude[$i + 1]);
            }
        }

        $this->onAfterCalculate($section);

    }

    public function onAfterCalculate($section) {
        $section->calculateSignsOfPoints();
        if ($section->houses) {
            $section->calculateHousesOfPoints();
        }
    }

    public static function houseSystems() {
        return [
            'koch' => 'K',
            'placidus' => 'P',
            'equal' => 'A',
            'alcabitius' => 'B',
            'campanus' => 'C',
            'equal_mc' => 'D',
            'morinus' => 'M',
            'whole_sign' => 'N',
            'porphyry' => 'O',
            'regiomontanus' => 'R',
            'sripati' => 'S',
            'topocentric' => 'T',
        ];
    }

    public static function houseSystemLabels() {
        return [
            'K' => 'Koch',
            'P' => 'Placidus',
            'A' => 'Equal House',
            'B' => 'Alkabitus',
            'C' => 'Kampanus',
            'D' => 'Equal from MC',
            'M' => 'Morinus',
            'N' => 'Sign-House',
            'O' => 'Porphirius',
            'R' => 'Regiomontanus',
            'S' => 'Sripati',
            'T' => 'Topocentric',
        ];
    }
}