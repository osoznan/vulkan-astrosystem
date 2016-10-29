<?php
/**
 * Widget which shows essential dignity for each astropoint, by sign and house
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package User Components
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\front\Components;

use vulkan\Core\Essential\EssDignity;
use vulkan\Helpers\Fmt;
use yii\helpers\Html;

class GeneralEssDignity extends \vulkan\System\Widget {

    function run() {
        $this->ess = EssDignity::getInstance('default');

        return $this->render('index');
    }
}