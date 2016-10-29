<?php
/**
 * Base class for scales which denote different divisions of Zodiac scale which attached
 * to a Vulkan section.
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

// provides

use vulkan\Core\C;

class SectionScale {

    const OUT_OF_RANGE_TEXT = 'sector number is out of range';

    /* @var $_sections \vulkan\Core\ChartSection */
    public $section;

    /* @var $chart \vulkan\Core\Chart */
    public $chart;

    /* @var $sectorCount position count or sector count (sector is distance between neighboring positions) */
    public $sectorCount;

    // angle positions of scale divisions which belong to a scale
    public $positions = [];

    public function section($section) {
        $this->section = $section;
        return $this;
    }

    public function getParent() {
        return $this->section;
    }

    public function positions(array $positions) {
        $this->positions = $positions;
        $this->sectorCount = count($this->positions);
        return $this;
    }

    public function getPositions() {
        return $this->positions;
    }

    public function modIndex(integer $index) {
        while ($index >= $this->sectorCount) {
            $index -= $this->sectorCount;
        }
        while ($index < 0) {
            $index += $this->sectorCount;
        }
        return $index;
    }

    public function checkIndexRange($value) {
        return $value >= 0 && $value < $this->sectorCount;
    }

    public function nextSectorIndex($sectorId) {
        if (!$this->checkIndexRange($sectorId)) {
            throw new \RangeException(_(self::OUT_OF_RANGE_TEXT));
        }
        if ($sectorId == $this->sectorCount - 1){
            return 0;
        } else {
            return $sectorId + 1;
        }
    }

    public function previousSectorIndex($sectorId) {
        if (!$this->checkIndexRange($sectorId)) {
            throw new \RangeException(_(self::OUT_OF_RANGE_TEXT));
        }
        if ($sectorId == 0){
            return $this->sectorCount - 1;
        } else {
            return $sectorId - 1;
        }
    }

    public function angleInSector(float $angle): float {
        return $angle - $this->positions[$this->sectorIndexFromAngle($angle)];
    }

    public function sectorIndexFromAngle($angle) {
        do {
            $current = current($this->positions);
            $next = next($this->positions);
            if (!$next) {
                $next = reset($this->positions) + C::R2PI;
                $next = modRad($next + C::R2PI);
            }
            if ($angle >= $current && $angle < $next) {
                return key($this->positions);
            }
        } while (true);
    }

    // angle between current and next position
    public function sectorAngleSpan($index): float {
        $nextPosition = $this->positions[$this->nextSectorIndex($index)];
        $position = $this->positions[$index];
        if ($nextPosition < $position) {
            return $nextPosition + C::R2PI - $position;
        } else {
            return $nextPosition - $position;
        }

    }

    public function distance($sect1Index, $sect2Index): float {
        return $this->positions[$sect1Index] - $this->positions[$sect2Index];
    }

}