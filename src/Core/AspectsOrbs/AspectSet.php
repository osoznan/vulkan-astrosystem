<?php
/**
 * Base class for a single aspect/orb table
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core\Aspects & Orbs
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\AspectsOrbs;

use vulkan\Core\AstroPoint;
use vulkan\Core\InfoObjects\AspectInfo;
use vulkan\System\C;
use vulkan\System\Config;

class AspectSet {

    const DEFAULT_ORB_INDEX = -1;

    /** @var float[][] Array of orbs. The 1st dimension is aspectId, the 2nd is orb column ordinal.  */
    public $orbs;
    /** @var float[][] Array of orb columns. The 1st dimension is orb column ordinal, the 2nd is
     * list of point ids with correspond to this column. */
    public $orbCols;
    /** @var boolean[] List of ids denoting active aspects (which are to calculate) */
    public $aspectsActive;

    /** @var string Name, i.e. "For transits" */
    public $title;
    /** @var string */
    public $description;
    /** @var float scaling factor for all orbs */
    public $coef = 1.0;

    public function __construct($params = null) {
        Config::settings($this, $params);
        if (!$this->aspectsActive) {
            $this->aspectsActive = [];
            for ($i = 0; $i < count(C::$aspectInfos); $i++) {
                $this->aspectsActive[] = true;
            }
        }
    }

    /**
     * Resets the orbId of each point to a default orb
     * @param AstroPoint[] $points
     */
    protected function _setOrbIdsAsDefault($points) {
        foreach ($points as $point) {
            $point->orbId = self::DEFAULT_ORB_INDEX;
        }
    }

    /**
     * Safe read of the given aspect's orbs
     * @param integer $aspectId
     * @return float[]
     * @throws \Exception
     */
    public function getAspectOrbs($aspectId) {
        if(isset($this->orbs[$aspectId])) {
            return $this->orbs[$aspectId];
        } else {
            throw new \Exception(_('Wrong aspect id'));
        }
    }

    public function toArrayMinimized() {
        return [
            'orbs' => $this->orbs,
            'coef' => $this->coef
        ];
    }

}