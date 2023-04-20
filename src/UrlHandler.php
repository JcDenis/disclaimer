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
use dcUrlHandlers;
use Dotclear\Database\Session;
use Dotclear\Helper\Network\Http;
use Dotclear\Helper\Network\UrlHandler as HelperHandler;

/**
 * @ingroup DC_PLUGIN_DISCLAIMER
 * @brief Public disclaimer - URL handler.
 * @since 2.6
 */
class UrlHandler extends dcUrlHandlers
{
    /**
     * Remove public callbacks (and serve disclaimer css)
     *
     * @param  null|string $args URL argument
     */
    public static function overwriteCallbacks(?string $args): void
    {
        if ($args == 'disclaimer.css') {
            self::serveDocument('disclaimer.css', 'text/css', false);
            exit;
        }
    }

    /**
     * Check disclaimer
     */
    public static function publicBeforeDocumentV2(): void
    {
        $s = dcCore::app()->blog->settings->get(My::id());

        # Test user-agent to see if it is a bot
        if (!$s->get('disclaimer_bots_unactive')) {
            $bots_agents = $s->get('diclaimer_bots_agents');
            $bots_agents = !$bots_agents ? My::DEFAULT_BOTS_AGENTS : explode(';', $bots_agents);

            $is_bot = false;
            foreach ($bots_agents as $bot) {
                if (stristr($_SERVER['HTTP_USER_AGENT'], $bot)) {
                    $is_bot = true;
                }
            }

            if ($is_bot) {
                return;
            }
        }

        # Set default-templates path for disclaimer files
        $tplset = dcCore::app()->themes->moduleInfo(dcCore::app()->blog->settings->get('system')->get('theme'), 'tplset');
        if (!empty($tplset) && is_dir(My::root() . '/default-templates/' . $tplset)) {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), My::root() . '/default-templates/' . $tplset);
        } else {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), My::root() . '/default-templates/' . DC_DEFAULT_TPLSET);
        }

        # New URL handler
        $urlHandler       = new HelperHandler();
        $urlHandler->mode = dcCore::app()->url->mode;
        $urlHandler->registerDefault([
            self::class,
            'overwriteCallbacks',
        ]);

        # Create session
        $session = new Session(
            dcCore::app()->con,
            dcCore::app()->prefix . 'session',
            My::SESSION_PREFIX . dcCore::app()->blog->id,
            '/'
        );
        $session->start();

        # Remove all URLs representations
        foreach (dcCore::app()->url->getTypes() as $k => $v) {
            $urlHandler->register(
                $k,
                $v['url'],
                $v['representation'],
                [self::class, 'overwriteCallbacks']
            );
        }

        # Get type
        $urlHandler->getDocument();
        $type = $urlHandler->type;
        unset($urlHandler);

        # Test cookie
        $cookie_name  = My::COOKIE_PREFIX . dcCore::app()->blog->id;
        $cookie_value = empty($_COOKIE[$cookie_name]) || !$s->get('disclaimer_remember') ?
                false : ($_COOKIE[$cookie_name]) == 1;

        # User say "disagree" so go away
        if (isset($_POST['disclaimerdisagree'])) {
            $session->destroy();
            if ($s->get('disclaimer_remember')) {
                setcookie($cookie_name, '0', time() - 86400, '/');
            }
            $redir = $s->get('disclaimer_redir');
            if (!$redir) {
                $redir = 'http://www.dotclear.org';
            }
            Http::redirect($redir);
            exit;
        }

        # Check if user say yes before
        elseif (!isset($_SESSION['sess_blog_disclaimer'])
         || $_SESSION['sess_blog_disclaimer'] != 1
        ) {
            if ($s->get('disclaimer_remember')
             && $cookie_value != false
            ) {
                $_SESSION['sess_blog_disclaimer'] = 1;

                return;
            }
            if (!empty($_POST['disclaimeragree'])) {
                $_SESSION['sess_blog_disclaimer'] = 1;

                if ($s->get('disclaimer_remember')) {
                    setcookie($cookie_name, '1', time() + 31536000, '/');
                }

                return;
            }

            $session->destroy();
            self::serveDocument('disclaimer.html', 'text/html', false);
            exit;
        }
    }
}
