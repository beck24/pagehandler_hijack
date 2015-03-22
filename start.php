<?php

/*
 * * Pagehandler Hijack
 * *
 * * @author Matt Beckett, matt@mattbeckett.me
 * * @copyright Matt Beckett 2015
 * * @link http://mattbeckett.me
 * * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * *
 */

namespace MBeckett\pagehandler_hijack;

const PLUGIN_ID = 'pagehandler_hijack';
const PLUGIN_VERSION = 20150321;

require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/hooks.php';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');

/**
 * setup our init code
 */
function init() {
	elgg_register_library(PLUGIN_ID . ':upgrades', __DIR__ . '/lib/upgrades.php');

	// add in our own css
	$url = elgg_get_simplecache_url('css', 'pagehandler_hijack');
	elgg_register_css('pagehandler_hijack', $url);

	//register action to save our plugin settings
	elgg_register_action("pagehandler_hijack/settings/save", __DIR__ . "/actions/settings.php", 'admin');

	//register plugin hooks
	// catch-all needed to catch our replacements
	elgg_register_plugin_hook_handler('route', 'all', __NAMESPACE__ . '\\router', 0);
	elgg_register_plugin_hook_handler('view', 'all', __NAMESPACE__ . '\\linkfix');
	elgg_register_plugin_hook_handler('entity:url', 'all', __NAMESPACE__ . '\\entity_url', 999);
	elgg_register_plugin_hook_handler('prepare', 'all', __NAMESPACE__ . '\\prepare_notification', 999);
	elgg_register_plugin_hook_handler('email', 'system', __NAMESPACE__ . '\\prepare_email', 999);


	$handlers = array_keys(get_replacement_handlers());
	foreach ($handlers as $h) {
		// specific handlers needed to catch route by priority since 'all' happens last regardless
		elgg_register_plugin_hook_handler('route', $h, __NAMESPACE__ . '\\router', 0);
	}
	
	elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrades');
}


function upgrades() {
	if (elgg_is_admin_logged_in()) {
		elgg_load_library(PLUGIN_ID . ':upgrades');
		run_function_once(__NAMESPACE__ . '\\upgrade_20150321');
	}
}