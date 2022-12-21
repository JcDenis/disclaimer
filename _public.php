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
if (!defined('DC_RC_PATH')) {
    return null;
}

dcCore::app()->blog->settings->addNamespace(basename(__DIR__));

# Is active
if (!dcCore::app()->blog->settings->get(basename(__DIR__))->get('disclaimer_active')) {
    return null;
}

# Localized l10n
__('Disclaimer');
__('Accept terms of uses');
__('I agree');
__('I disagree');

# Templates
dcCore::app()->tpl->addValue('DisclaimerTitle', function ($attr) {
    return '<?php echo ' . sprintf(
        dcCore::app()->tpl->getFilters($attr),
        'dcCore::app()->blog->settings->get("disclaimer")->get("disclaimer_title")'
    ) . '; ?>';
});

dcCore::app()->tpl->addValue('DisclaimerText', function ($attr) {
    return '<?php echo dcCore::app()->blog->settings->get("disclaimer")->get("disclaimer_text"); ?>';
});

dcCore::app()->tpl->addValue('DisclaimerFormURL', function ($attr) {
    return '<?php dcCore::app()->blog->url; ?>';
});

# Behaviors
dcCore::app()->addBehavior('publicHeadContent', function () {
    echo dcUtils::cssModuleLoad(basename(__DIR__) . '/css/disclaimer.css');
});

dcCore::app()->addBehavior(
    'publicBeforeDocumentV2',
    ['urlDisclaimer', 'publicBeforeDocumentV2']
);

/**
 * @ingroup DC_PLUGIN_DISCLAIMER
 * @brief Public disclaimer - URL handler.
 * @since 2.6
 */
class urlDisclaimer extends dcUrlHandlers
{
	private const COOKIE_PREFIX = 'dc_disclaimer_cookie_';

    public static $default_bots_agents = [
        'bot','Scooter','Slurp','Voila','WiseNut','Fast','Index','Teoma',
        'Mirago','search','find','loader','archive','Spider','Crawler',
    ];

    /**
     * Remove public callbacks (and serve disclaimer css)
     *
     * @param  array $args Arguments
     */
    public static function overwriteCallbacks($args)
    {
        if ($args == 'disclaimer.css') {
            self::serveDocument('disclaimer.css', 'text/css', false);
            exit;
        }

        return null;
    }

    /**
     * Check disclaimer
     */
    public static function publicBeforeDocumentV2()
    {
        $s = dcCore::app()->blog->settings->addNamespace(basename(__DIR__));

        # Test user-agent to see if it is a bot
        if (!$s->get('disclaimer_bots_unactive')) {
            $bots_agents = $s->get('diclaimer_bots_agents');
            $bots_agents = !$bots_agents ? self::$default_bots_agents : explode(';', $bots_agents);

            $is_bot = false;
            foreach ($bots_agents as $bot) {
                if (stristr($_SERVER['HTTP_USER_AGENT'], $bot)) {
                    $is_bot = true;
                }
            }

            if ($is_bot) {
                return null;
            }
        }

        # Set default-templates path for disclaimer files
        $tplset = dcCore::app()->themes->moduleInfo(dcCore::app()->blog->settings->get('system')->get('theme'), 'tplset');
        if (!empty($tplset) && is_dir(__DIR__ . '/default-templates/' . $tplset)) {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/default-templates/' . $tplset);
        } else {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/default-templates/' . DC_DEFAULT_TPLSET);
        }

        # New URL handler
        $urlHandler       = new urlHandler();
        $urlHandler->mode = dcCore::app()->url->mode;
        $urlHandler->registerDefault([
            'urlDisclaimer',
            'overwriteCallbacks',
        ]);

        # Create session
        $session = new sessionDB(
            dcCore::app()->con,
            dcCore::app()->prefix . 'session',
            'dc_disclaimer_sess_' . dcCore::app()->blog->id,
            '/'
        );
        $session->start();

        # Remove all URLs representations
        foreach (dcCore::app()->url->getTypes() as $k => $v) {
            $urlHandler->register(
                $k,
                $v['url'],
                $v['representation'],
                ['urlDisclaimer', 'overwriteCallbacks']
            );
        }

        # Get type
        $urlHandler->getDocument();
        $type = $urlHandler->type;
        unset($urlHandler);

        # Test cookie
        $cookie_name  = self::COOKIE_PREFIX . dcCore::app()->blog->id;
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
            http::redirect($redir);
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

                return null;
            }
            if (!empty($_POST['disclaimeragree'])) {
                $_SESSION['sess_blog_disclaimer'] = 1;

                if ($s->get('disclaimer_remember')) {
                    setcookie($cookie_name, '1', time() + 31536000, '/');
                }

                return null;
            }

            $session->destroy();
            self::serveDocument('disclaimer.html', 'text/html', false);
            exit;
        }

        return null;
    }
}
