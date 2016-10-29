<?php
/**
 * Accentuation widget
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package User Components
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\front\Components;

use vulkan\Core\Essential\EssDignity;
use vulkan\Core\SignScale;

class Accentuation extends \vulkan\System\Widget {

    function run() {
        $this->rows = [['', 'Fire', 'Earth', 'Air', 'Water', 'Card.', 'Fix.', 'Mut.']];

        foreach ($this->sections as $section) {
            $signElem = [0, 0, 0, 0];
            $houseElem = [0, 0, 0, 0];
            $signCross = [0, 0, 0];
            $houseCross = [0, 0, 0];

            foreach (array_slice($section->points, 0, 10) as $point) {
                $sign = $point->getSignId();
                $house = $point->getHouseId();
                $signElem[SignScale::getElement($sign)]++;
                $houseElem[SignScale::getElement($house)]++;
                $signCross[SignScale::getCross($sign)]++;
                $houseCross[SignScale::getCross($house)]++;
            }

            $this->rows[] = $section->title;
            $this->rows[] = array_merge(['sign'], $signElem, $signCross);
            $this->rows[] = array_merge(['house'], $houseElem, $houseCross);
        }

        return $this->render('SimpleTable');
    }
}