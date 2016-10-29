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

use vulkan\Core\AstroMoment;
use vulkan\Core\ChartSectionInfo;
use vulkan\Core\Location;
use vulkan\front\Components\GeneralSingleChart;

class Index extends \vulkan\front\Controllers\Controller {

    public function actionIndex() {

        $chartWidget = new GeneralSingleChart([
            'width' => $_GET['width'] ?? 600,
            'height' => $_GET['height'] ?? 600,
            'innerRadius' => $_GET['innerRadius'] ?? 180
        ]);

        $section = $chartWidget->chart->section;

        $section->info = new ChartSectionInfo();
        $section->info->moment = new AstroMoment($_GET['dateTime']);

        if (!isset($_GET['lon']) || !isset($_GET['lat'])) {
            $chartWidget->chart->globalHousesScale->visible = false;
            $section->info->location = new Location($_GET['lon'], $_GET['lat']);
        }

        foreach ([
                     $_GET['dateTime'], 'GMT ' . $_GET['gmt'],
                     Location::floatToLongitude($_GET['lon']), Location::floatToLatitude($_GET['lat']),
                     $_GET['place']] as $item) {
            if ($item) {
                $inputData[] = $item;
            }
        }

        $this->assign([
            'chartBlock' => $chartWidget->run(),
            'title' => $_GET['title'],
            'inputData' => join(', ', $inputData),
            'description' => $_GET['description'],
            'bottomTitle' => $_GET['bottomTitle'],
            'bottomDescription' => $_GET['bottomDescription']
        ]);

        return $this->render('index');

        /*
         * hsys='k'
         *
         * date=1991-06-18
         * time=18:15:00
         * gmt=3
         * lon=112.11.22
         * lat=-22.11.21
         *
         * style=default-chart
         *
         * width
         * height
         * innerRadius
         * sectionWidth
         *
         */
    }

}