<?php
/**
 * Widget building a cross table of aspects
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package User Components
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\front\Components;

use vulkan\Helpers\Fmt;
use vulkan\Helpers\Strings;
use vulkan\System\Vulkan;

class AspectListCrossTable extends \vulkan\System\Widget {

    function run() {

        $s = '<table class="vul-t-data-table">'
            . ($this->title ? '<caption>' . $this->title . '</caption>' : '') . '<td></td>';

        foreach ($this->chart->aspectManager->points1 as $point) {
            $s .= '<td>' . $this->print($point->info->caption, $point) . '</td>';
        }

        $s .= '</tr>';

        foreach ($this->chart->aspectManager->points1 as $point1) {
            $s .= '<tr><td>' . $this->print($point1->info->caption, $point1) . '</td>';
            foreach ($this->chart->aspectManager->points2 as $point2) {
                if ($point1->info->id != $point2->info->id) {
                    $s .= '<td>' . $this->print($this->getAspectByPointsFromList($point1->info->id, $point2->info->id), [$point1, $point2]) . '</td>';
                } else {
                    $s .= '<td>*</td>';
                }
            }
        }

        $this->content = $s . '</table>';
        $this->totalAspects = count($this->chart->aspectManager->aspectList);

        return $this->render('index');
    }

    function getAspectByPointsFromList($pointId1, $pointId2) {
        foreach ($this->chart->aspectManager->aspectList as $aspect) {
            if ($aspect) {
                list($id1, $id2) = [$aspect->point1->info->id, $aspect->point2->info->id];
                if (($id1 == $pointId1 && $id2 == $pointId2)) {
                    return (is_object($aspect) ? $aspect->aspectData->caption : '');
                }
                if (($id1 == $pointId2 && $id2 == $pointId1)) {
                    return '-';
                }
            }
        }
    }


}