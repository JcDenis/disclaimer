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
use dcPage;
use dcNsProcess;
use dcSettings;
use Dotclear\Helper\Html\Form\{
    Checkbox,
    Div,
    Label,
    Input,
    Note,
    Para,
    Text,
    Textarea
};
use Dotclear\Helper\Html\Html;
use Exception;

class Backend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && !is_null(dcCore::app()->auth) && !is_null(dcCore::app()->blog) // nullsafe PHP < 8.0
            && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
                dcCore::app()->auth::PERMISSION_CONTENT_ADMIN,
            ]), dcCore::app()->blog->id);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->addBehaviors([
            'adminBeforeBlogSettingsUpdate' => function (dcSettings $blog_settings): void {
                $s = $blog_settings->get(My::id());

                try {
                    $s->put('disclaimer_active', isset($_POST['disclaimer_active']));
                    $s->put('disclaimer_remember', isset($_POST['disclaimer_remember']));
                    $s->put('disclaimer_redir', $_POST['disclaimer_redir'] ?? '');
                    $s->put('disclaimer_title', $_POST['disclaimer_title'] ?? '');
                    $s->put('disclaimer_text', $_POST['disclaimer_text'] ?? '');
                    $s->put('disclaimer_bots_unactive', isset($_POST['disclaimer_bots_unactive']));
                    $s->put('disclaimer_bots_agents', $_POST['disclaimer_bots_agents'] ?? '');
                } catch (Exception $e) {
                    $s->drop('disclaimer_active');
                    $s->put('disclaimer_active', 0);
                }
            },

            'adminBlogPreferencesHeaders' => function (): string {
                return dcPage::jsModuleLoad(My::id() . '/js/backend.js');
            },

            'adminPostEditorTags' => function (string $editor, string $context, ArrayObject $alt_tags, string $format): void {
                if ($context == 'blog_desc') {
                    $alt_tags->append('#disclaimer_text');
                }
            },

            'adminBlogPreferencesFormV2' => function (dcSettings $blog_settings): void {
                $s = $blog_settings->get(My::id());

                $disclaimer_bots_agents = $s->get('disclaimer_bots_agents');
                if (empty($disclaimer_bots_agents)) {
                    $disclaimer_bots_agents = implode(';', My::DEFAULT_BOTS_AGENTS);
                }

                echo
                (new Div())->class('fieldset')->items([
                    (new Text('h4', My::name()))->id('disclaimerParam'),
                    (new Div())->class('two-boxes even')->items([
                        (new Para())->items([
                            (new Checkbox('disclaimer_active', (bool) $s->get('disclaimer_active')))->value(1),
                            (new Label(__('Enable disclaimer'), Label::OUTSIDE_LABEL_AFTER))->for('disclaimer_active')->class('classic'),
                        ]),
                        (new Para())->items([
                            (new Label(__('Title:')))->for('disclaimer_title'),
                            (new Input('disclaimer_title'))->size(30)->maxlenght(255)->value(Html::escapeHTML((string) $s->get('disclaimer_title'))),
                        ]),
                    ]),
                    (new Div())->class('two-boxes odd')->items([
                        (new Para())->items([
                            (new Checkbox('disclaimer_remember', (bool) $s->get('disclaimer_remember')))->value(1),
                            (new Label(__('Remember the visitor'), Label::OUTSIDE_LABEL_AFTER))->for('disclaimer_remember')->class('classic'),
                        ]),
                        (new Para())->items([
                            (new Label(__('Link output:')))->for('disclaimer_redir'),
                            (new Input('disclaimer_redir'))->size(30)->maxlenght(255)->value(Html::escapeHTML((string) $s->get('disclaimer_redir'))),
                        ]),
                        (new Note())->class('form-note')->text(__('Leave blank to redirect to the site Dotclear')),
                    ]),
                    (new Div())->class('clear')->items([
                        (new Para())->items([
                            (new Label(__('Disclaimer:'), Label::OUTSIDE_LABEL_BEFORE))->for('disclaimer_text'),
                            (new Textarea('disclaimer_text', Html::escapeHTML((string) $s->get('disclaimer_text'))))->cols(60)->rows(5)->lang($blog_settings->get('system')->get('lang'))->spellcheck(true),
                        ]),
                        (new Para())->items([
                            (new Label(__('List of robots allowed to index the site pages (separated by semicolons):')))->for('disclaimer_bots_agents'),
                            (new Input('disclaimer_bots_agents'))->size(120)->maxlenght(255)->value(Html::escapeHTML($disclaimer_bots_agents)),
                        ]),
                        (new Para())->items([
                            (new Checkbox('disclaimer_bots_unactive', (bool) $s->get('disclaimer_bots_unactive')))->value(1),
                            (new Label(__('Disable the authorization of indexing by search engines'), Label::OUTSIDE_LABEL_AFTER))->for('disclaimer_bots_unactive')->class('classic'),
                        ]),
                    ]),
                ])->render();
            },
        ]);

        return true;
    }
}
