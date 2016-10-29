<?php
/**
 * Widget for displaying the basic horoscope info (astropoint - longitude - house)
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package User Components
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace vulkan\front\Components;

use vulkan\Core\C;
use vulkan\Helpers\Fmt;

class GeneralConstellations extends \vulkan\System\Widget {

    /* @var $chart \vulkan\Components\VisualChart */
    public $chart;

    function run() {

        foreach ($this->sections as $section) {
            $s = '<table class="vul-t-data-table">'
                . ($this->title ? '<caption>' . $this->title . ($section->title ? '(' . $section->title . ')' : '') . '</caption>' : '');

            foreach ($section->points as $point) {
                $s .= sprintf('<tr><td>%s</td><td class="centered">%s</td><td>%s</td><td>%s</td></tr>',
                    $aspect->point1->info->caption, $aspect->aspectData->info->angleDegree,
                    $aspect->point2->info->caption, Fmt::floatToDegMin($aspect->accuracy()));
            }

            $s .= '</table>';
        }

        return $s;
    }
}