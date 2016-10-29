<?php
/**
 * Base config file
 */

return [
    'dir' => [
        'base' => __DIR__ . '/../../',
        'frontend' =>  __DIR__ . '\\..\\..\\front\\',
        'config' => __DIR__
    ],
    'controller' => [
        'class' => \vulkan\front\Controllers\Controller::class
    ],
    'view' => [
        'class' => \vulkan\System\View::class
    ],
    'aspectManager' => [
        'class' => \vulkan\Core\AspectsOrbs\AspectManager::class,
    ],
    'aspectSet' => [
        'class' => \vulkan\Core\AspectsOrbs\GeneralAspectSet::class,
    ],
    'credits' => [
        'author' => 'Zemlyansky Alexander',
        'authorNatalInfo' => 'sun:Capricorn;moon:Libra;mercury:Capricorn;Venus:Aquarius;Mars:Virgo',
        'site' => 'http://astrolog-online.net',
        'email' => 'meraponimaniya@mail.ru'
    ]
];