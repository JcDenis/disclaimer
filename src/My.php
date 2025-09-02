<?php

declare(strict_types=1);

namespace Dotclear\Plugin\disclaimer;

use Dotclear\Module\MyPlugin;

/**
 * @brief       disclaimer My helper.
 * @ingroup     disclaimer
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class My extends MyPlugin
{
    /**
     * Default list of bots agents.
     *
     * @var     array<int, string>  DEFAULT_BOTS_AGENTS
     */
    public const DEFAULT_BOTS_AGENTS = [
        'bot',
        'Scooter',
        'Slurp',
        'Voila',
        'WiseNut',
        'Fast',
        'Index',
        'Teoma',
        'Mirago',
        'search',
        'find',
        'loader',
        'archive',
        'Spider',
        'Crawler',
    ];

    // Use default permissions
}
