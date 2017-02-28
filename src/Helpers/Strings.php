<?php
/**
 * Strings helper. There will be added the useful string functions.
 *
 * @copyright Copyright &copy; 2016-2017 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Helpers;

class Strings
{

    static function shortClassName($classPath) {
        return substr($class = $classPath, strrpos($classPath, '\\') + 1);
    }
}