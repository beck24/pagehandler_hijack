<?php

namespace MBeckett\pagehandler_hijack;

/**
 * hook called on view, all
 * check if there is an internal link using an old handler
 * convert it to a new handler
 * 
 * @param type $hook
 * @param type $type
 * @param type $returnvalue
 * @param type $params
 * @return array
 */
function linkfix($hook, $type, $returnvalue, $params) {
	if (elgg_get_viewtype() == 'failsafe') {
		return $returnvalue;
	}
	return handler_replace($returnvalue);
}


/**
 * hook called on route, all
 * check if $returnvalue['handler'] to see if we need to replace it
 * if the handler is an original handler, we want to foward it to the new url
 * 
 * @param type $hook
 * @param type $type
 * @param type $returnvalue
 * @param type $params
 * @return array
 */
function router($hook, $type, $returnvalue, $params) {
	if (elgg_get_config('pagehandler_hijack')) {
		return $returnvalue;
	}

	$handlers = get_replacement_handlers();

	if (in_array($returnvalue['handler'], array_keys($handlers))) {
		// we have been given an old handler -> we should forward to the replacement
		// probably from an old link or something
		$currenturl = current_page_url();

		//get everything after the pagehandler
		$afterhandler = str_replace(elgg_get_site_url() . $returnvalue['handler'], "", $currenturl);

		$newurl = elgg_get_site_url() . $handlers[$returnvalue['handler']] . $afterhandler;

		// forward to the new url
		forward($newurl);
	}

	if (in_array($returnvalue['handler'], $handlers)) {
		// we need to do something about it
		// get the original handler
		$original = array_search($returnvalue['handler'], $handlers);

		if (!empty($original)) {
			
			// reset the context for non-hijack aware code
			elgg_set_context($original);
			
			// let the system load content for the original handler
			$returnvalue['handler'] = $original;
			$returnvalue['identifier'] = $original;
			
			// set a flag so we don't infinite loop ourselves in route hooks
			elgg_set_config('pagehandler_hijack', true);

			return elgg_trigger_plugin_hook('route', $original, null, $returnvalue);
		}
	}
}


/**
 * switch out handlers on any entity url
 * 
 * @param type $hook
 * @param type $type
 * @param type $returnvalue
 * @param type $params
 * @return type
 */
function entity_url($hook, $type, $returnvalue, $params) {
	$url = elgg_normalize_url($returnvalue);
	return handler_replace($url);
}


/**
 * switch out handlers on any notification content
 * 
 * @param type $hook
 * @param type $type
 * @param \Elgg_Notifications_Notification $notification
 * @param type $params
 * @return \Elgg_Notifications_Notification
 */
function prepare_notification($hook, $type, $notification, $params) {
	if (!($notification instanceof \Elgg\Notifications\Notification)) {
		return $notification;
	}

	$notification->body = handler_replace($notification->body);
	$notification->subject = handler_replace($notification->subject);

	return $notification;
}


/**
 * switch out handlers on any outgoing email
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return array();
 */
function prepare_email($hook, $type, $return, $params) {
	if (!is_array($return)) {
		return $return;
	}
	
	if ($return['subject']) {
		$return['subject'] = handler_replace($return['subject']);
	}
	if ($return['body']) {
		$return['body'] = handler_replace($return['body']);
	}

	return $return;
}