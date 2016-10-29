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

use vulkan\Helpers\Fmt;

class GeneralPointInfo extends \vulkan\System\Widget {

    function run() {

        foreach ($this->sections as $section) {
            $s .= '<table class="vul-t-data-table"><caption>'
                .  ($this->title ? '<caption>' . ucfirst($this->title) . ($section->title ? ' (' . $section->title . ')' : '') . '</caption>' : '')
                . '</caption>';

            $houses = [];
            foreach ($section->houses as $item) {
                $houses[] = $item->getPos();
            }

            foreach ($section->points as $point) {
                $s .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                    $point->info->caption . ($point->isRetro() ? '<sup>R</sup>' : ''),
                    Fmt::floatToDegSignMin($point->getDegree()),
                    Fmt::floatToDegMinSec($point->getDegree()),

                    $section->houses[getHouse(modRad($point->position), $houses)]->info->caption
                );
            }
            $s .= '</table>';
        }

        return $this->render($s);
    }
}

