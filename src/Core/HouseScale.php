<?php
/**
 * Scale for houses
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

class HouseScale extends SectionScale {

    public static $abbrToOrdinal = [
        'asc' => 0, 'h1' => 0,
        'h2' => 1, 'h3' => 2,
        'ic' => 3, 'h4' => 3,
        'h5' => 4, 'h6' => 5,
        'dsc' => 6, 'h7' => 6,
        'h8' => 7, 'h9' => 8,
        'mc' => 9, 'h10' => 9,
        'h11' => 10, 'h12' => 11
    ];

    /** @var ChartSection Section which houses are set
     * (yes, houses haven't be sticked to a native section only) */
    public $attachedSection;

    public function attachedSection($section) {
        $this->attachedSection = $section;
        $this->sectorCount = 12;
        return $this;
    }

    /**
     * Gets an angle from a given house to the next house (a house width)
     * @param integer $houseId
     * @return float Angle
     */
    public function getHouseWidth($houseId) {
        if ($houseId <> 11) {
            $distance = abs($this->attachedSection->houses[$houseId]->position
                - $this->attachedSection->houses[$houseId != 11 ? $houseId + 1 : 0]->position);
        } else {
            $distance = abs(reset($this->attachedSection->houses)->position
                - end($this->attachedSection->houses)->position);
        }
        return min($distance, _2PI - $distance);
    }

    /**
     * Zodiac Signs (their ids) which are involved in this house
      * @return integer[] Sign ids list
     */
    public function getIncludedSigns($houseId) {
        $sign1 = $this->attachedSection->houses[$houseId]->getSignId();
        $sign2 = $this->attachedSection->houses[$houseId != 11 ? $houseId + 1 : 0]->getSignId();
        $signs = [];
        $curSign = $sign1;
        $sign2 = $sign2 >= $sign1 ? $sign2 : $sign2 + 12;
        for ($i = $sign1; $i <= $sign2; $i++) {
            $signs[] = $curSign;
            $curSign = ($curSign < 11 ? ++$curSign : 0);
        }
        
        return $signs;
    }

    public static function getHouse($position, $houses) {
        $i = 0;
        do {
            $current = current($houses)->position;
            $next = next($houses);
            if ($next->position < $current) {
                $next->position += _2PI;
            } elseif (!$next) {
                return 11;
            }
            if ($position >= $current && $position < $next->position) {
                return $i;
            }
            $i++;
        } while (true);
    }

}