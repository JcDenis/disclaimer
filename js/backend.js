/*global $, dotclear, jsToolBar */
'use strict';

$(() => {
  if (typeof jsToolBar === 'function') {
    $('#disclaimer_text').each(function () {
      const tbWidgetTextDisclaimer = new jsToolBar(this);
      tbWidgetTextDisclaimer.draw('xhtml');
    });
  }
});