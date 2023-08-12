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
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // Module specs
        $mod_conf = [
            [
                'disclaimer_active',
                'Enable disclaimer plugin',
                false,
                'boolean',
            ],
            [
                'disclaimer_remember',
                'Remember the visitor who has already accepted the terms',
                false,
                'boolean',
            ],
            [
                'disclaimer_redir',
                'Redirection if disclaimer is refused',
                'https://www.google.fr',
                'string',
            ],
            [
                'disclaimer_title',
                'Title for disclaimer',
                'Disclaimer',
                'string',
            ],
            [
                'disclaimer_text',
                'Description for disclaimer',
                __('<p>You must accept this term before entering</p>'),
                //'You must accept this term before entering',
                'string',
            ],
            [
                'disclaimer_bots_unactive',
                'Bypass disclaimer for bots',
                false,
                'boolean',
            ],
            [
                'disclaimer_bots_agents',
                'List of know bots',
                implode(';', [
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
                ]),
                'string',
            ],
        ];

        try {
            // Settings
            foreach ($mod_conf as $v) {
                My::settings()->put(
                    $v[0],
                    $v[2],
                    $v[3],
                    $v[1],
                    false,
                    true
                );
            }

            return true;
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
