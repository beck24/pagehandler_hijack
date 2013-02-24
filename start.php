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
  elgg_extend_view('css/admin', 'pagehandler_hijack/css');
  
  //register action to save our plugin settings
  elgg_register_action("pagehandler_hijack/settings/save", elgg_get_plugins_path() . "pagehandler_hijack/actions/settings.php", 'admin');
   
  //register plugin hooks
  elgg_register_plugin_hook_handler('route', 'all', 'pagehandler_hijack_route', 0);
  elgg_register_plugin_hook_handler('view', 'all', 'pagehandler_hijack_linkfix');
  elgg_register_plugin_hook_handler('register', 'all', 'pagehandler_hijack_menufix', 9999);
  
}
  
// Initialise this plugin
elgg_register_event_handler('init','system','pagehandler_hijack_init');
