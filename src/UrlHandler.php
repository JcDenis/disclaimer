<?php

declare(strict_types=1);

namespace Dotclear\Plugin\disclaimer;

use Dotclear\App;
use Dotclear\Helper\Network\Http;
use Dotclear\Helper\Network\UrlHandler as HelperHandler;

/**
 * @brief       disclaimer frontend URL handler class.
 * @ingroup     disclaimer
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class UrlHandler
{
    /**
     * Remove public callbacks (and serve disclaimer css)
     *
     * @param  null|string $args URL argument
     */
    public static function overwriteCallbacks(?string $args): void
    {
        if ($args == 'disclaimer.css') {
            App::url()::serveDocument('disclaimer.css', 'text/css', false);
            exit;
        }
    }

    /**
     * Check disclaimer
     */
    public static function publicBeforeDocumentV2(): void
    {
        if (!App::blog()->isDefined()) {
            return;
        }

        # Test user-agent to see if it is a bot
        if (!My::settings()->getBool('disclaimer_bots_unactive', false)) {
            $bots_agents = My::settings()->getStr('diclaimer_bots_agents', false);
            $bots_agents = $bots_agents === '' ? My::DEFAULT_BOTS_AGENTS : explode(';', $bots_agents);

            $is_bot = false;
            foreach ($bots_agents as $bot) {
                if (isset($_SERVER['HTTP_USER_AGENT']) && is_string($_SERVER['HTTP_USER_AGENT']) && stristr($_SERVER['HTTP_USER_AGENT'], $bot)) {
                    $is_bot = true;
                }
            }

            if ($is_bot) {
                return;
            }
        }

        # Set default-templates path for disclaimer files
        $tplset = App::themes()->getDefine(App::blog()->settings()->get('system')->getStr('theme', false))->get('tplset');
        if (!is_string($tplset) || empty($tplset) || !is_dir(implode(DIRECTORY_SEPARATOR, [My::path(), 'default-templates', $tplset]))) {
            $tplset = App::config()->defaultTplset();
        }
        App::frontend()->template()->appendPath(implode(DIRECTORY_SEPARATOR, [My::path(), 'default-templates', $tplset]));

        # New URL handler
        $urlHandler = new HelperHandler();
        $urlHandler->setMode(App::url()->getMode());
        $urlHandler->registerDefault(self::overwriteCallbacks(...));

        # Start session if not
        App::session()->start();

        # Remove all URLs representations
        foreach (App::url()->getTypes() as $k => $v) {
            $urlHandler->register(
                $k,
                $v['url'],
                $v['representation'],
                self::overwriteCallbacks(...)
            );
        }

        # Get type
        $urlHandler->getDocument();
        unset($urlHandler);

        # User say "disagree" so go away
        if (isset($_POST['disclaimerdisagree'])) {
            App::session()->destroy();
            $redir = My::settings()->getStr('disclaimer_redir', false);
            if ($redir === '') {
                $redir = 'http://www.dotclear.org';
            }
            Http::redirect($redir);
            exit;
        # User say or said yes
        } elseif (isset($_POST['disclaimeragree'])
            || App::session()->get('sess_blog_disclaimer') != ''
        ) {
            App::session()->set('sess_blog_disclaimer', 1);

            return;
        # User never said agree
        } else {
            App::session()->set('sess_blog_disclaimer', 0);
            App::url()::serveDocument('disclaimer.html', 'text/html', false);
            exit;
        }
    }
}
