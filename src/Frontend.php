<?php

declare(strict_types=1);

namespace Dotclear\Plugin\disclaimer;

use ArrayObject;
use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief       disclaimer frontend class.
 * @ingroup     disclaimer
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
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
        App::frontend()->template()->addValue('DisclaimerTitle', function (ArrayObject $attr): string {
            return '<?php echo ' . sprintf(
                App::frontend()->template()->getFilters($attr),
                My::class . '::settings()->get("disclaimer_title")'
            ) . '; ?>';
        });

        App::frontend()->template()->addValue('DisclaimerText', function (ArrayObject $attr): string {
            return '<?php echo ' . My::class . '::settings()->get("disclaimer_text"); ?>';
        });

        App::frontend()->template()->addValue('DisclaimerFormURL', function (ArrayObject $attr): string {
            return '<?php App::blog()->url(); ?>';
        });

        # Behaviors
        App::behavior()->addBehaviors([
            'publicHeadContent' => function (): void {
                echo My::cssLoad('disclaimer');
            },
            'publicBeforeDocumentV2' => UrlHandler::publicBeforeDocumentV2(...),
        ]);

        return true;
    }
}
