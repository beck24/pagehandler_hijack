<?php

/*
 * 	This is the form to set the plugin settings
 * 
 * 	Naming conventions for this plugin:
 * 		handlers = default handlers registered to the system
 * 		hijacks = replacement handlers dealt with by this plugin
 */

// only admins can see this page
admin_gatekeeper();
global $CONFIG;

$title  = elgg_echo('pagehandler_hijack:settings');

//get current pagehandlers
$handlers = array_keys($CONFIG->pagehandler);
sort($handlers);


// start form
$form = "<div style=\"margin: 15px;\">";
$form .= "<pre>" . print_r($languages,1) . "</pre>";

// preamble & explanation
$form .= "<h1>" . elgg_echo('pagehandler_hijack:settings') . "</h1><br><br>";
$form .= elgg_echo('pagehandler_hijack:disclaimer') . "<br><br>";
		
// get an array of our hijacks
$hijacks = pagehandler_hijack_get_replacements();
		
// generate our form
$form .= "<div class=\"pagehandler_hijack_form_element_wrapper\">";
$form .= "<table><tr>";
$form .= "<td class=\"pagehandler_hijack_thead\">" . elgg_echo('pagehandler_hijack:default') . "</td>";
$form .= "<td class=\"pagehandler_hijack_thead\">" . elgg_echo('pagehandler_hijack:replacement') . "</td>";
$form .= "</tr>";
$count = 0;
foreach($handlers as $handler){
  $count++;
  if($count % 2){
    $zebra = "phh-odd";
  }
  else{
    $zebra = "phh-even";
  }
  
  $form .= "<tr>";
  $form .= "<td class=\"pagehandler_hijack_element_default {$zebra}\">{$handler}</td>";
  $form .= "<td class=\"pagehandler_hijack_element_replacement {$zebra}\">";
  $form .= elgg_view('input/hidden', array('name' => 'default[]', 'value' => $handler));
  $form .= elgg_view('input/text', array('name' => 'replacement[]', 'value' => $hijacks[$handler]));
  $form .= "</td>";
  $form .= "</tr>";
}
$form .= "</table>";
$form .= "</div><!-- /pagehandler_hijack_form_element_wrapper -->";
$form .= "<br><br>";

$form .= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('submit')));


// parameters for form generation - enctype must be 'multipart/form-data' for file uploads 
$form_vars = array();
$form_vars['body'] = $form;
$form_vars['name'] = 'update_pagehandler_hijack_settings';
$form_vars['action'] = elgg_get_site_url()."action/pagehandler_hijack/settings";

// create the form
$area =  elgg_view('input/form', $form_vars);

// place the form into the elgg layout
$body = elgg_view_layout('one_column', array('content' => $area));

// display the page
echo elgg_view_page($title, $body);