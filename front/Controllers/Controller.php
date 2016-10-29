<?php
/**
 * Custom controller which is run by default. It adds an ephemeris calculation method.
 * You can set a default controller in the Vulkan config file (the 'controller.class' option)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package FrontEnd
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\front\Controllers;

use vulkan\Core\ChartSection;
use vulkan\System\Vulkan;

class Controller extends \vulkan\System\Controller {

    public static $nameSpace = '\\vulkan\\front\\Controllers';

    public function beforeAction($action) {
        parent::beforeAction($action);

        $this->getView()->addCssFile('default-layout.css');
        $this->getView()->addCssFile('examples.css');

        ChartSection::$onCalculate = function($section) {

            chdir(__DIR__ . "/../Swetest/");
            $date = date("d.m.Y", $section->info->moment->dateTime);
            $time = date('H:i:s', $section->info->moment->dateTime - $section->info->moment->gmt);

            $section->houseSystem = 'A';

            exec ("swetest -edir./Sweph -b{$date} -ut{$time} -p0123456789DAttt -eswe -house{$section->info->location->longitude},{$section->info->location->latitude},{$section->houseSystem} -fldsj -g, -head", $out);
            $longitude = [];
            foreach ($out as $key => $line) {
                $row = explode(',',$line);
                $longitude[$key] = $row[0];
                $speed[$key] = $row[2];
            }

            for ($i = 0; $i < count($section->points); $i++) {
                $section->points[$i]->position = degRad($longitude[$i]);
                $section->points[$i]->speed = degRad($speed[$i]);
            }

            for ($i = 15; $i <= 26; $i++) {
                $section->houses[$i - 15]->position = degRad($longitude[$i + 1]);
            }

            $section->calculateHousesOfPoints();
        };
    }

}