<?php

namespace vulkan\Core\AspectsOrbs;

use vulkan\Core\C;

class AspectData {

    public $info;
    public $orbs;
    public $active = true;

    public function __construct($infoId, $orbs, $active = true) {
        $this->info = C::$aspectInfos[$infoId];
        $this->orbs = $orbs;
        $this->active = $active;
    }

    function toArray() {
        return [
            'id' => $this->info->id,
            'orbs' => $this->orbs,
        ];
    }

    function toArrayMinimized() {
        return [
            'id' => $this->info->id,
            'orbs' => $this->orbs,
            'active' => $this->active
        ];
    }

}