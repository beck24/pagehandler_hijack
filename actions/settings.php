<?php

// get our inputs
$defaults = get_input('default');
$replacements = get_input('replacement');


// sanity check
if(!is_array($defaults) || !is_array($replacements) || count($defaults) != count($replacements)){
  register_error(elgg_echo('pagehandler_hijack:error'));
  forward(REFERER);
}

$replacements = array_unique(array_filter($replacements));


//create full array of handlers: original => replacement
$handlers = array();
foreach($replacements as $key => $replacement){
  if(!empty($replacement) && !empty($defaults[$key])){
    $handlers["{$defaults[$key]}"] = $replacement;
  }
}

elgg_set_plugin_setting('hijacks', serialize($handlers), 'pagehandler_hijack');

system_message(elgg_echo('pagehandler_hijack:settings:saved'));
forward(REFERER);