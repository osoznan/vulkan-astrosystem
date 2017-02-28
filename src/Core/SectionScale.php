<?php
/**
 * Base class for scales which denote different divisions of Zodiac scale which attached
 * to a Vulkan section.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

// provides

use vulkan\System\C;

class SectionScale {

    const OUT_OF_RANGE_TEXT = 'sector number is out of range';

    /** @var $_sections \vulkan\Core\ChartSection */
    public $section;
    /** @var $chart \vulkan\Core\Chart */
    public $chart;
    /** @var integer Positions count or sector count (sector is distance between neighboring positions) */
    public $sectorCount;
    /** @var float[] Angle positions of scale divisions which belong to a scale */
    public $positions = [];

    /**
     * @param ChartSection $section
     * @return $this
     */
    public function section($section) {
        $this->section = $section;
        return $this;
    }

    /**
     * Gets a parent element (section)
     * @return ChartSection
     */
    public function getParent() {
        return $this->section;
    }

    /**
     * Sets the positions (in radians) and sector count.
     * @param float[] $positions
     * @return float Angle
     */
    public function positions(array $positions) {
        $this->positions = $positions;
        $this->sectorCount = count($this->positions);
        return $this;
    }

    /**
     * Normalizes a sector index.
     * Say if we have 0..11 index range, 12 turns to 0, 13 to 1, -1 to 11.
     * @param integer $index
     * @return integer
     */
    public function modIndex($index) {
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

    /**
     * Next ordinal num of a given sector index
     * @param integer $sectorId
     * @return integer Next index
     */
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

    /**
     * Previous ordinal num of a given sector index
     * @param integer $sectorId
     * @return integer Previous index
     */
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

    /**
     * Gets a sector for a given angle and returns an angle from the beginning of a sector to a given angle
     * @param float $angle
     * @return float Angle
     */
    public function angleInSector($angle) {
        return $angle - $this->positions[$this->sectorIndexFromAngle($angle)];
    }

    /**
     * Gets a sector for a given angle
     * @param float $angle
     * @return integer Ordinal number of a sector
     */
    public function sectorIndexFromAngle($angle) {
        do {
            $current = current($this->positions);
            $next = next($this->positions);
            if (!$next) {
                $next = reset($this->positions) + _2PI;
                $next = modRad($next + _2PI);
            }
            if ($angle >= $current && $angle < $next) {
                return key($this->positions);
            }
        } while (true);
    }

    /**
     * Gets an angle between the current and next position
     * @param integer $index
     * @return float Angle
     */
    public function sectorAngleSpan($index) {
        $nextPosition = $this->positions[$this->nextSectorIndex($index)];
        $position = $this->positions[$index];
        if ($nextPosition < $position) {
            return $nextPosition + _2PI - $position;
        } else {
            return $nextPosition - $position;
        }

    }

    /**
     * Gets a distance between two sectors
     * @param $sect1Index int First sector index
     * @param $sect2Index int Second sector index
     * @return float Distance (angle)
     */
    public function distance($sect1Index, $sect2Index) {
        return $this->positions[$sect1Index] - $this->positions[$sect2Index];
    }

}