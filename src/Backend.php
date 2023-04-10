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
if (!defined('DC_CONTEXT_ADMIN')) {
    return null;
}

dcCore::app()->addBehavior('adminBeforeBlogSettingsUpdate', function (dcSettings $blog_settings) {
    $s = $blog_settings->addNamespace(basename(__DIR__));

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
});

dcCore::app()->addBehavior('adminBlogPreferencesHeaders', function () {
    $editor = dcCore::app()->auth->getOption('editor');

    return
        dcCore::app()->callBehavior('adminPostEditor', $editor['xhtml'], 'disclaimer', ['#disclaimer_text'], 'xhtml') .
        dcPage::jsModuleLoad(basename(__DIR__) . '/js/admin.js');
});

dcCore::app()->addBehavior('adminBlogPreferencesFormV2', function (dcSettings $blog_settings) {
    $s = $blog_settings->addNamespace(basename(__DIR__));

    $disclaimer_bots_agents = $s->get('disclaimer_bots_agents');
    if (empty($disclaimer_bots_agents)) {
        $disclaimer_bots_agents = 'bot;Scooter;Slurp;Voila;WiseNut;Fast;Index;Teoma;' .
        'Mirago;search;find;loader;archive;Spider;Crawler';
    }

    echo
    '<div class="fieldset">' .
    '<h4 id="disclaimerParam">' . __('Disclaimer') . '</h4>' .
    '<div class="two-boxes">' .

    '<p><label class="classic" for="disclaimer_active">' .
    form::checkbox(
        'disclaimer_active',
        '1',
        (bool) $s->get('disclaimer_active')
    ) .
    __('Enable disclaimer') . '</label></p>' .

    '<p><label for="disclaimer_title">' . __('Title:') . '</label>' .
    form::field(
        'disclaimer_title',
        30,
        255,
        html::escapeHTML((string) $s->get('disclaimer_title'))
    ) .
    '</p>' .

    '</div><div class="two-boxes">' .

    '<p><label class="classic">' .
    form::checkbox(
        'disclaimer_remember',
        '1',
        (bool) $s->get('disclaimer_remember')
    ) .
    __('Remember the visitor') . '</label></p>' .

    '<p><label for="disclaimer_redir">' . __('Link output:') . '</label>' .
    form::field(
        'disclaimer_redir',
        30,
        255,
        html::escapeHTML((string) $s->get('disclaimer_redir'))
    ) . '</p>' .
    '<p class="form-note info">' . __('Leave blank to redirect to the site Dotclear') . '</p>' .

    '</div><div class="clear">' .

    '<p class="area"><label for="disclaimer_text">' . __('Disclaimer:') . '</label>' .
    form::textarea(
        'disclaimer_text',
        60,
        5,
        [
            'default'    => html::escapeHTML((string) $s->get('disclaimer_text')),
            'extra_html' => 'lang="' . dcCore::app()->blog->settings->get('system')->get('lang') . '" spellcheck="true"',
        ]
    ) . '</p>' .

    '<p><label for="disclaimer_bots_agents">' . __('List of robots allowed to index the site pages (separated by semicolons):') . '</label>' .
    form::field(
        'disclaimer_bots_agents',
        120,
        255,
        html::escapeHTML($disclaimer_bots_agents)
    ) . '</p>' .

    '<p><label for="disclaimer_bots_unactive">' .
    form::checkbox(
        'disclaimer_bots_unactive',
        '1',
        (bool) $s->get('disclaimer_bots_unactive')
    ) .
    __('Disable the authorization of indexing by search engines') .
    '</label></p>' .

    '</div>' .
    '</div>';
});
