<?php
/**
 * The main Vulkan config file
 */

return [
    'dir' => [
        // base dir of Vulkan AstroSystem
        'base' => __DIR__ . '/../../',
        'front' =>  __DIR__ . '/../../front/',
        'data' => __DIR__ . '/../Data/',
        'config' => __DIR__ . '/'
    ],
    // default classes for the Vulkan elements
    'ephemerisAdapter' => [
        'class' => \vulkan\front\Classes\SwetestEphemeris::class
    ],
    'chartSection' => [
        'class' => \vulkan\Core\ChartSection::class
    ],
    'aspectManager' => [
        'class' => \vulkan\Core\AspectsOrbs\AspectManager::class,
    ],
    'aspectSet' => [
        'class' => \vulkan\Core\AspectsOrbs\GeneralAspectSet::class,
        'file' => 'default'
    ],
    'essDignity' => [
        'file' => 'default'
    ],
    // some funny props, about author info
    'credits' => [
        'author' => 'Zemlyansky Alexander',
        'authorNatalInfo' => ['sun' => 'Capricorn', 'moon' => 'Libra', 'mercury' => 'Capricorn', 'Venus' => 'Aquarius', 'Mars' => 'Virgo'],
        'site' => 'http://astrolog-online.net',
        'email' => 'meraponimaniya@mail.ru'
    ]
];