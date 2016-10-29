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

use vulkan\Core\C;
use vulkan\Helpers\Fmt;

class AspectListTable extends \vulkan\System\Widget {

    function run() {

        $this->rows = [];

        foreach ($this->chart->aspectManager->aspectList as $aspect) {
            $this->rows[] = [$aspect, $aspect->point1->info->caption, $aspect->aspectData->angleDegree,
                $aspect->point2->info->caption, Fmt::floatToDegMinSec($aspect->accuracy())];
        }

        return $this->render('index');
    }
}