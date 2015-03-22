<?php

namespace MBeckett\pagehandler_hijack;

elgg_load_css('pagehandler_hijack');

$title  = elgg_echo('pagehandler_hijack:settings');

//get current pagehandlers
// private API, but no choice, was out-voted
$handlers = array_filter(array_keys(_elgg_services()->router->getPageHandlers()));
sort($handlers);

echo "<div style=\"margin: 15px;\">";
echo "<h1>" . elgg_echo('pagehandler_hijack:settings') . "</h1><br><br>";
echo elgg_echo('pagehandler_hijack:disclaimer') . "<br><br>";

// get an array of our hijacks
$hijacks = get_replacement_handlers();

echo "<div class=\"pagehandler_hijack_form_element_wrapper\">";
echo "<table><tr>";
echo "<td class=\"pagehandler_hijack_thead\">" . elgg_echo('pagehandler_hijack:default') . "</td>";
echo "<td class=\"pagehandler_hijack_thead\">" . elgg_echo('pagehandler_hijack:replacement') . "</td>";
echo "</tr>";

$count = 0;
foreach($handlers as $handler){
  $count++;
  if($count % 2){
    $zebra = "phh-odd";
  }
  else{
    $zebra = "phh-even";
  }
  
  echo "<tr>";
  echo "<td class=\"pagehandler_hijack_element_default {$zebra}\">{$handler}</td>";
  echo "<td class=\"pagehandler_hijack_element_replacement {$zebra}\">";
  echo elgg_view('input/hidden', array('name' => 'default[]', 'value' => $handler));
  echo elgg_view('input/text', array('name' => 'replacement[]', 'value' => $hijacks[$handler]));
  echo "</td>";
  echo "</tr>";
}
echo "</table>";
echo "</div><!-- /pagehandler_hijack_form_element_wrapper -->";
echo "<br><br>";