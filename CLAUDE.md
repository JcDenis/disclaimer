# disclaimer

Notes for AI assistants working on the Dotclear plugin disclaimer.

**Read first** the shared conventions of the author's Dotclear modules:
https://raw.githubusercontent.com/JcDenis/gh2da/master/CONVENTIONS.md
(if a web tool refuses the address, use `curl -s` on it from the shell).
The rules below take precedence for this module.

## Module

It add a disclaimer to your blog entrance. This plugin is inspired from plugin "Private mode" by Osku.

No admin page. Public side: behaviors.

Required Dotclear and PHP versions: see `_define.php`.

## Structure

* `src/Backend.php`: admin side: menu entry, favorites, behaviors registration.
* `src/Frontend.php`: public side: behaviors, template tags and URL handlers registration.
* `src/Install.php`: install and update, default settings.
* `src/My.php`: module helpers (`My::id()`, settings, permissions).
* `src/Uninstall.php`: cleaners for the plugin Uninstaller.
* `src/UrlHandler.php`: frontend URL handler class.
* `js/`: `backend.js`.
* `default-templates/`: public templates.
* Behaviors used: `adminBeforeBlogSettingsUpdate`, `adminBlogPreferencesHeaders`, `adminPostEditorTags`, `adminBlogPreferencesFormV2`, `publicHeadContent`, `publicBeforeDocumentV2`.

## Pitfalls

* The disclaimer blocks public pages until the visitor agrees. URL types in `UrlHandler::EXCLUDED_URL_TYPES` (ApiServer, so the ClaudetteMcp connector) and those added with the behavior `disclaimerExcludedUrlTypes` must stay served as usual: blocking them broke the connector before 1.9.

## Testing

* CI browses the common admin and public pages; extra pages can be listed in `.github/dotclear-test.txt`.
