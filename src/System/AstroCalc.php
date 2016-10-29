<?php
/**
 * Basic global routines of Vulkan AstroSystem
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package System
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

define('PI', M_PI);
define('PI_2', M_PI / 2);
define('_2PI', M_PI * 2);

function frac($x): float{
    $x = abs($x);
    return $x - floor($x);
}

function sign($x) {
    return ($x > 0) - ($x < 0);
}

function radDeg($x): float{
    return $x * 180 / PI;
}

function degRad($x) {
    return $x * PI / 180;
}
function normalizeByAscRad(&$min, &$max) {
    if ($max < $min) {
        $max += _2PI;
    }
}

#----------------------------------------------------------------------
function decimalToMinutes($x): float {
    return $x * 60.0 / 100.0;
}

function minutesToDecimal($x) {
    return $x * 100.0 / 60;
}

function floatToDegMinSec($degree) {
    $degInt = intval(abs($degree));
    $minutes = (abs($degree) - $degInt) * 60;
    $seconds = ($minutes - intval($minutes)) * 60;
    return [$degInt, intval(round($minutes, 5)), round($seconds) == 60 ? 0: floor($seconds), ($degree < 0 ? -1 : 1)];
}

#-----------------------------------------------------------
function modDeg($degree) {
    while ($degree > 360) {
        $degree -= 360;
    }
    while ($degree < 0) {
        $degree += 360;
    }
    return $degree;
}

function modRad($radian){
    while ($radian > _2PI) {
        $radian -= _2PI;
    }
    while ($radian < 0) {
        $radian += _2PI;
    }
    return $radian;
}

function mod180($degree): float{
    while ($degree > 180) {
        $degree -= 180;
    }
    while ($degree < 0) {
        $degree += 180;
    }
    return $degree;
}

function modPi($radian){
    while ($radian > PI) {
        $radian -= PI;
    }
    while ($radian < 0) {
        $radian += PI;
    }
    return $radian;
}

function isInRange($min, $max, $value) {
    return $value >= $min && $value <= $max;
}

function isInRange12($x): bool{
    return $x >= 0 and $x < 12;
}

function isInRange360($x): bool{
    return $x >= 0 and $x < 360;
}

// ------------------------------------------------------
function getHouse($position, $houses) {
    $i = 0;
    do {
        $current = current($houses);
        $next = next($houses);
        if ($next < $current) {
            $next += _2PI;
        } elseif (!$next) {
            return 0;
        }
        if ($position >= $current && $position <= $next) {
            return $i;
        }
        $i++;
    } while (true);
}