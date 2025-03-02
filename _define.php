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
        'settings'    => ['blog' => '#params.' . $this->id . 'Param'],
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-02-24T23:31:12+00:00',
    ]
);
