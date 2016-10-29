<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2016
 * Time: 12:24
 */

namespace vulkan\Front\Controllers;

use vulkan\front\Classes\GeneralSingleChart;
use vulkan\Helpers\Fmt;
use vulkan\Core\Vulkan;

class Tests extends \vulkan\Core\Controller {

    public function actionIndex() {
        error_reporting(E_NOTICE);

        $a = new \vulkan\TestCase\Core\ChartSection;
        $a = new \vulkan\TestCase\Core\SectionScale;
        $a = new \vulkan\TestCase\Core\EqualSectionScale;
        //$a = new \vulkan\TestCase\Core\BaseAstroPoint;
        $a = new \vulkan\TestCase\Core\InfoObject;

        $a = new \vulkan\TestCase\Core\AstroCalc;
        $a = new \vulkan\TestCase\Helpers\tFmt;
        $a = new \vulkan\TestCase\Core\AspectManager;
    }
}