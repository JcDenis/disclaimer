<?php
/**
 * @file
 * @brief       The plugin disclaimer definition
 * @ingroup     disclaimer
 *
 * @defgroup    disclaimer Plugin disclaimer.
 *
 * Add a disclaimer to your blog entrance.
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

$this->registerModule(
    'Disclaimer',
    'Add a disclaimer to your blog entrance',
    'Jean-Christian Denis, Pierre Van Glabeke',
    '1.5.1',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'settings'    => ['blog' => '#params.' . basename(__DIR__) . 'Param'],
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/issues',
        'details'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository'  => 'https://github.com/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
    ]
);
