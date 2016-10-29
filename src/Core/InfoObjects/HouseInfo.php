<?php
/**
 * House info, necessary for storing each of defined houses
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\InfoObjects;

class HouseInfo extends InfoObject {
    public $caption;

    public $element;
    public $cross;

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

    public function __construct($id, $name, $alias, $caption) {
        parent::__construct($id, $name, $alias);
        $this->caption = $caption;
        $this->element = $id % 3;
        $this->cross = $id % 4;
    }

    public function __toString() {
        return (string)printf('House %s', $this->name);
    }
}