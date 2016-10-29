<?php
/**
 * Scale for houses
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use vulkan\Core\SectionScale;

class HouseScale extends SectionScale {

    public $attachedSection;

    public function attachedSection($sectionKey) {
        $this->attachedSection = $sectionKey;
        $this->sectorCount = count($this->positions);
        return $this;
    }

    public function sectorsByHouse($count) {
        $this->sectorsByHouse = $count;

        $positions = [];

        foreach($this->attachedSection->houses as $house) {
            $curr = $house->position;
            $next = $this->attachedSection->houses[$house->info->id < 12 ? $house->info->id + 1 : 0]->position;
            normalizeByAscRad($curr, $next);
            $step = ($next - $curr) / $this->sectorsByHouse;

            for ($i = 0; $i < $this->sectorsByHouse; $i++) {
                $positions[] = $curr + $step * $i;
            }

        }

        $this->positions($positions);

        return $this;
    }

}