<?php
/**
 * Base class which describes a chart.
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core;

use Seld\JsonLint\DuplicateKeyException;
use vulkan\Core\AspectsOrbs\AspectManager;
use vulkan\System\Config;
use vulkan\System\Transfer;
use vulkan\Core\Vulkan;

class Chart {

    public $parent;
    public $key;

    public $title;

    /* @var array $sections \vulkan\Visual\VisualChartSection */
    public $sections = [];

    /* @var $aspectManager \vulkan\Core\AspectsOrbs\AspectManager */
    public $aspectManager;

    public function __construct($params) {
        Config::settings($this, $params);
    }

    public function getParent() {
        return $this->parent;
    }

    public function getSection($key) {
        if (isset($this->sections[$key])) {
            return $this->sections[$key];
        } else {
            throw new \Exception(_('Wrong section key'));
        }
    }

    public function sections($sections) {
        foreach ($sections as $section) {
            $sect = new $section['class']();
            Config::settings($sect, $section);
            $this->addSection($sect, $section['key']);
        }
        return $this;
    }

    public function addSection($section, $key) {
        if (!isset($this->sections[$key])) {
            $section->chart($this);
            $section->key = $key;
            $this->sections[$key] = $section;
        } else {
            throw new \Exception(_('Duplicate section key'));
        }
        foreach($section->scales as $scale) {
            $scale->chart = $this;
        }
        return $this;
    }

    public function aspectedPoints($points1, $points2) {
        $this->aspectManager->aspectedPoints($points1, $points2);
        $this->aspectManager->calculateAspects();
        return $this;
    }

    public function toArray() {
        return array_merge(
            Transfer::serializeObjectVars($this,
                'key', 'centerX', 'centerY', 'innerRadius', 'direction', 'rotationAngle',
                'aspectManager', 'sections')
        );
    }

}