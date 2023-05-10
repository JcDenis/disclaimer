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

use ArrayObject;
use dcCore;
use dcNsProcess;
use dcUtils;

class Frontend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_RC_PATH');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        // nullsafe PHP < 8.0
        if (is_null(dcCore::app()->blog)) {
            return false;
        }

        # Is active
        if (!dcCore::app()->blog->settings->get(My::id())->get('disclaimer_active')) {
            return false;
        }

        # Localized l10n
        __('Disclaimer');
        __('Accept terms of uses');
        __('I agree');
        __('I disagree');

        # Templates
        dcCore::app()->tpl->addValue('DisclaimerTitle', function (ArrayObject $attr): string {
            return '<?php echo ' . sprintf(
                dcCore::app()->tpl->getFilters($attr),
                'dcCore::app()->blog->settings->get("disclaimer")->get("disclaimer_title")'
            ) . '; ?>';
        });

        dcCore::app()->tpl->addValue('DisclaimerText', function (ArrayObject $attr): string {
            return '<?php echo dcCore::app()->blog->settings->get("disclaimer")->get("disclaimer_text"); ?>';
        });

        dcCore::app()->tpl->addValue('DisclaimerFormURL', function (ArrayObject $attr): string {
            return '<?php dcCore::app()->blog->url; ?>';
        });

        # Behaviors
        dcCore::app()->addBehaviors([
            'publicHeadContent' => function (): void {
                echo dcUtils::cssModuleLoad(My::id() . '/css/disclaimer.css');
            },
            'publicBeforeDocumentV2' => [UrlHandler::class, 'publicBeforeDocumentV2'],
        ]);

        return true;
    }
}
