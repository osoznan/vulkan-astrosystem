<?php
/**
 * The entry point of entry points. Any thing starts here!
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package User
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

define("DIR_SLASH", '\\');

$config = require('src/config/base.php');

$controller = $_GET['ctrl'] ?? 'index';

$vulkan = new \vulkan\System\Application;
$vulkan->run($config, $controller, $_GET['action']);