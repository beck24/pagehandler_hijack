<?php	

/*
** Pagehandler Hijack
**
** @author Matt Beckett, matt@clever-name.com
** @copyright Matt Beckett 2011
** @link http://clever-name.com
** @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
**
*/

// include our helper functions
include 'lib/functions.php';


// setup our init code
function pagehandler_hijack_init(){
  
  // add in our own css
  elgg_extend_view('css/elgg', 'pagehandler_hijack/css');
  
  //register action to save our plugin settings
  elgg_register_action("pagehandler_hijack/settings", elgg_get_plugins_path() . "pagehandler_hijack/actions/settings.php", 'admin');
  
  //register events
  elgg_register_event_handler('pagesetup','system','pagehandler_hijack_pagesetup');
  
  //register page handlers
  elgg_register_page_handler('pagehandler_hijack','pagehandler_hijack_page_handler');
  
  //register plugin hooks
  elgg_register_plugin_hook_handler('route', 'all', 'pagehandler_hijack_route');
  elgg_register_plugin_hook_handler('view', 'all', 'pagehandler_hijack_linkfix');
  
}


function pagehandler_hijack_page_handler(){
  if(!include(elgg_get_plugins_path() . "pagehandler_hijack/pages/edit.php")){
    return FALSE;
  }
  
  return TRUE;
}


function pagehandler_hijack_pagesetup() {

	if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
	  $item = new ElggMenuItem('pagehandler_hijack', elgg_echo('pagehandler_hijack:settings'), elgg_get_site_url() . 'pagehandler_hijack/admin/');
	  $item->setParent('settings');
	  elgg_register_menu_item('page', $item);
	}
}
  
// Initialise this plugin
elgg_register_event_handler('init','system','pagehandler_hijack_init');


?>
