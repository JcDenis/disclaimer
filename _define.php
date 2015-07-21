<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of disclaimer, a plugin for Dotclear 2.
# 
# Copyright (c) 2009-2015 Jean-Christian Denis and contributors
# 
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) {

	return null;
}
 
$this->registerModule(
	/* Name */
	"disclaimer",
	/* Description*/
	"Add a disclaimer to your blog entrance",
	/* Author */
	"Jean-Christian Denis, Pierre Van Glabeke",
	/* Version */
	'0.9',
	/* Properties */
	array(
		'permissions' => 'admin',
		'type' => 'plugin',
		'dc_min' => '2.8',
		'support' => 'http://forum.dotclear.org/viewtopic.php?id=40000',
		'details' => 'http://plugins.dotaddict.org/dc2/details/disclaimer'
	)
);