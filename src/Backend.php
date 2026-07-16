<?php

declare(strict_types=1);

namespace Dotclear\Plugin\disclaimer;

use ArrayObject, Exception;
use Dotclear\App;
use Dotclear\Helper\Process\TraitProcess;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Img;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Note;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Textarea;
use Dotclear\Helper\Html\Html;
use Dotclear\Interface\Core\BlogSettingsInterface;

/**
 * @brief       disclaimer backend class.
 * @ingroup     disclaimer
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Backend
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        App::behavior()->addBehaviors([
            'adminBeforeBlogSettingsUpdate' => function (BlogSettingsInterface $blog_settings): void {
                $s = $blog_settings->get(My::id());

                try {
                    $s->put('disclaimer_active', isset($_POST['disclaimer_active']));
                    $s->put('disclaimer_redir', isset($_POST['disclaimer_redir']) && is_string($_POST['disclaimer_redir']) ? $_POST['disclaimer_redir'] : '');
                    $s->put('disclaimer_title', isset($_POST['disclaimer_title']) && is_string($_POST['disclaimer_title']) ? $_POST['disclaimer_title'] : '');
                    $s->put('disclaimer_text', isset($_POST['disclaimer_text']) && is_string($_POST['disclaimer_text']) ? $_POST['disclaimer_text'] : '');
                    $s->put('disclaimer_bots_unactive', isset($_POST['disclaimer_bots_unactive']));
                    $s->put('disclaimer_bots_agents', isset($_POST['disclaimer_bots_agents']) && is_string($_POST['disclaimer_bots_agents']) ? $_POST['disclaimer_bots_agents'] : '');
                } catch (Exception $e) {
                    $s->drop('disclaimer_active');
                    $s->put('disclaimer_active', 0);
                }
            },

            'adminBlogPreferencesHeaders' => function (): string {
                return My::jsLoad('backend');
            },

            'adminPostEditorTags' => function (string $editor, string $context, ArrayObject $alt_tags, string $format): void {
                if ($context == 'blog_desc') {
                    $alt_tags->append('#disclaimer_text');
                }
            },

            'adminBlogPreferencesFormV2' => function (BlogSettingsInterface $blog_settings): void {
                $s = $blog_settings->get(My::id());

                $disclaimer_bots_agents = $s->getStr('disclaimer_bots_agents', false);
                if (empty($disclaimer_bots_agents)) {
                    $disclaimer_bots_agents = implode(';', My::DEFAULT_BOTS_AGENTS);
                }

                echo
                (new Fieldset(My::id() . '_params'))
                    ->legend(new Legend((new Img(My::icons()[0]))->class('icon-small')->render() . ' ' . My::name()))->items([
                    (new Div())->class('two-boxes even')->items([
                        (new Para())->items([
                            (new Checkbox('disclaimer_active', $s->getBool('disclaimer_active', false)))->value(1),
                            (new Label(__('Enable disclaimer'), Label::OUTSIDE_LABEL_AFTER))->for('disclaimer_active')->class('classic'),
                        ]),
                        (new Para())->items([
                            (new Label(__('Title:')))->for('disclaimer_title'),
                            (new Input('disclaimer_title'))->size(30)->maxlength(255)->value(Html::escapeHTML($s->getStr('disclaimer_title', false))),
                        ]),
                    ]),
                    (new Div())->class('two-boxes odd')->items([
                        (new Para())->items([
                            (new Label(__('Link output:')))->for('disclaimer_redir'),
                            (new Input('disclaimer_redir'))->size(30)->maxlength(255)->value(Html::escapeHTML($s->getStr('disclaimer_redir', false))),
                        ]),
                        (new Note())->class('form-note')->text(__('Leave blank to redirect to the site Dotclear')),
                    ]),
                    (new Div())->class('clear')->items([
                        (new Para())->items([
                            (new Label(__('Disclaimer:'), Label::OUTSIDE_LABEL_BEFORE))->for('disclaimer_text'),
                            (new Textarea('disclaimer_text', Html::escapeHTML($s->getStr('disclaimer_text', false))))->cols(60)->rows(5)->lang($blog_settings->get('system')->getStr('lang', false))->spellcheck(true),
                        ]),
                        (new Para())->items([
                            (new Label(__('List of robots allowed to index the site pages (separated by semicolons):')))->for('disclaimer_bots_agents'),
                            (new Input('disclaimer_bots_agents'))->size(120)->maxlength(255)->value(Html::escapeHTML($disclaimer_bots_agents)),
                        ]),
                        (new Para())->items([
                            (new Checkbox('disclaimer_bots_unactive', $s->getBool('disclaimer_bots_unactive', false)))->value(1),
                            (new Label(__('Disable the authorization of indexing by search engines'), Label::OUTSIDE_LABEL_AFTER))->for('disclaimer_bots_unactive')->class('classic'),
                        ]),
                    ]),
                ])->render();
            },
        ]);

        return true;
    }
}
