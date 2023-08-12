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
use Dotclear\Core\Process;

class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        # Is active
        if (!My::settings()->get('disclaimer_active')) {
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
                My::class . '::settings()->get("disclaimer_title")'
            ) . '; ?>';
        });

        dcCore::app()->tpl->addValue('DisclaimerText', function (ArrayObject $attr): string {
            return '<?php echo ' . My::class . '::settings()->get("disclaimer_text"); ?>';
        });

        dcCore::app()->tpl->addValue('DisclaimerFormURL', function (ArrayObject $attr): string {
            return '<?php dcCore::app()->blog->url; ?>';
        });

        # Behaviors
        dcCore::app()->addBehaviors([
            'publicHeadContent' => function (): void {
                echo My::cssLoad('disclaimer');
            },
            'publicBeforeDocumentV2' => [UrlHandler::class, 'publicBeforeDocumentV2'],
        ]);

        return true;
    }
}
