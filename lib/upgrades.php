<?php

namespace MBeckett\pagehandler_hijack;

/**
 * add version tracking number to upgraded installations
 * 
 * @return boolean
 */
function upgrade_20150321() {
	$version = (int) elgg_get_plugin_setting('version', PLUGIN_ID);
	
	if ($version && $version >= PLUGIN_VERSION) {
		return true;
	}
	
	elgg_set_plugin_setting('version', 20150321, PLUGIN_ID);
}