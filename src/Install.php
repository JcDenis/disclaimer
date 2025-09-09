<?php

declare(strict_types=1);

namespace Dotclear\Plugin\disclaimer;

use Dotclear\App;
use Dotclear\Helper\Process\TraitProcess;
use Exception;

/**
 * @brief       disclaimer install class.
 * @ingroup     disclaimer
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Install
{
    use TraitProcess;

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
            App::error()->add($e->getMessage());
        }

        return true;
    }
}
