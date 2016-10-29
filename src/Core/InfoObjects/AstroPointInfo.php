<?php
/**
 * Class which holds info of one of defined points
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

class AstroPointInfo extends InfoObject
{
    public $caption;

    public function __construct($id, $name, $alias, $caption) {
        parent::__construct($id, $name, $alias);
        $this->caption = $caption;
    }

}

