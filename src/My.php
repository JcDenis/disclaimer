<?php
/**
 * @brief disclaimer, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author Jean-Christian Denis, Pierre Van Glabeke
 *
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\disclaimer;

use dcCore;

/**
 * This module definitions.
 */
class My
{
    /** @var    array   Default list of bots agents */
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

    /** @var    string  disclaimer specific cookie prefix */
    public const COOKIE_PREFIX = 'dc_disclaimer_cookie_';

    /** @var    string  disclaimer specific session prefix */
    public const SESSION_PREFIX = 'dc_disclaimer_sess_';

    /**
     * This module id.
     */
    public static function id(): string
    {
        return basename(dirname(__DIR__));
    }

    /**
     * This module name.
     */
    public static function name(): string
    {
        $name = dcCore::app()->plugins->moduleInfo(self::id(), 'name');

        return __(is_string($name) ? $name : self::id());
    }

    /**
     * This module path.
     */
    public static function path(): string
    {
        return dirname(__DIR__);
    }
}
