<?php

// gets an associative array of replacement handlers
function pagehandler_hijack_get_replacements(){
  $hijackstring = elgg_get_plugin_setting('hijacks', 'pagehandler_hijack');

  if(!empty($hijackstring)){
    $hijacks = unserialize($hijackstring);
  }
  if(!is_array($hijacks)){
    $hijacks = array();
  }
  
  $hijacks = array_filter($hijacks);
  
  return $hijacks;
}

// hook called on view, all
// check if there is an internal link using an old handler
// convert it to a new handler
function pagehandler_hijack_linkfix($hook, $type, $returnvalue, $params){
  $handlers = pagehandler_hijack_get_replacements();
  
  foreach($handlers as $original => $replacement){
    $search = "href=\"" . elgg_get_site_url() . $original . "/";
    $replace = "href=\"" . elgg_get_site_url() . $replacement . "/";
    $returnvalue = str_ireplace($search, $replace, $returnvalue);
  }
  
  return $returnvalue;
}


// hook called on route, all
// check if $returnvalue['handler'] to see if we need to replace it
// if the handler is an original handler, we want to foward it to the new url
function pagehandler_hijack_route($hook, $type, $returnvalue, $params){
  $handlers = pagehandler_hijack_get_replacements();
  
  if(in_array($returnvalue['handler'], array_keys($handlers))){
    // we have been given an old handler -> we should forward to the replacement
    // probably from an old link or something
    $currenturl = current_page_url();
    
    //get everything after the pagehandler
    $afterhandler = str_replace(elgg_get_site_url() . $returnvalue['handler'], "", $currenturl);
    
    $newurl = elgg_get_site_url() . $handlers[$returnvalue['handler']] . $afterhandler;
    
    // forward to the new url
    forward($newurl);
    
    // prevent code execution after the forward
    exit;
  }
  
  if(in_array($returnvalue['handler'], $handlers)){
    // we need to do something about it
    // get the original handler
    $original = array_search($returnvalue['handler'], $handlers);
    
    if(!empty($original)){
      // reset the context
      elgg_set_context($original);
      // let the system load content for the original handler
      $returnvalue['handler'] = $original;
      
      return $returnvalue;
    }
  }
}