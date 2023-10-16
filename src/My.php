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
     * @var     array   DEFAULT_BOTS_AGENTS
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

    /**
     * disclaimer specific cookie prefix.
     *
     * @var     string  COOKIE_PREFIX
     */
    public const COOKIE_PREFIX = 'dc_disclaimer_cookie_';

    /**
     * disclaimer specific session prefix.
     *
     * @var     string  SESSION_PREFIX
     */
    public const SESSION_PREFIX = 'dc_disclaimer_sess_';

    // Use default permissions
}
