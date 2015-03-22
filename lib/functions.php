<?php

namespace MBeckett\pagehandler_hijack;

/**
 * gets an associative array of replacement handlers
 * 
 * @return array
 */
function get_replacement_handlers() {
	static $handlers;
	
	if (is_array($handlers)) {
		return $handlers;
	}
	
	$handlerstring = elgg_get_plugin_setting('hijacks', 'pagehandler_hijack');

	if (!empty($handlerstring)) {
		$handlers = unserialize($handlerstring);
	}
	
	if (!is_array($handlers)) {
		$handlers = array();
	}

	$handlers = array_filter($handlers);

	return $handlers;
}


function handler_replace($content) {
	$handlers = get_replacement_handlers();

	foreach ($handlers as $original => $replacement) {
		
		$search = elgg_get_site_url() . $original . "/";
		$replace = elgg_get_site_url() . $replacement . "/";
		$content = str_ireplace($search, $replace, $content);
	}
	
	return $content;
}