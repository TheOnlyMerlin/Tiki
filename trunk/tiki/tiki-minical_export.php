<?php
require_once('tiki-setup.php');
include_once('lib/minical/minicallib.php');

if($feature_minical != 'y') {
  die;  
}

if(!$user) {
  die;  
}

function _csv($item) {
  $item = str_replace('"', '""', $item);
//  if (strpos($item, ",") !== FALSE) {
    $item = '"' . $item . '"';
//  }
  return $item;
}

$events=$minicallib->minical_list_events($user,0,-1,'start_desc','');

header("Content-type: text/plain");
//header( "Content-Disposition: attachment; filename=$file" );
header( "Content-Disposition: inline; filename=tiki-calendar" );
print('"Subject","Start Date","Start Time","End Date","End Time","All day event","Reminder on/off","Reminder Date","Reminder Time","Meeting Organizer","Required Attendees","Optional Attendees","Meeting Resources","Billing Information","Categories","Description","Location","Mileage","Priority","Private","Sensitivity","Show time as"');

print("\r\n");
foreach($events['data'] as $event) {
  $line = Array();
  $line[] = _csv($event['title']);
  $line[] = _csv(date("n/j/Y",$event['start']));
  $line[] = _csv(date("g:i:s A",$event['start']));
  $line[] = _csv(date("n/j/Y",$event['end']));
  $line[] = _csv(date("g:i:s A",$event['end']));
  $line[] = _csv('False');
  if($minical_reminders) {
  	$line[] = _csv('True');
    $line[] = _csv(date("n/j/Y",$event['start']-$minical_reminders));
    $line[] = _csv(date("g:i:s A",$event['start']-$minical_reminders));
  } else {
	$line[] = _csv('False');
	$line[] = _csv('');
	$line[] = _csv('');
  }
  $line[]='';
  $line[]='';
  $line[]='';
  $line[]='';
  $line[]='';
  $line[]='';    
  $line[]='';
  $line[]=_csv($event['description']);
  $line[]='';
  $line[]=_csv('Normal');    
  $line[]=_csv('False');
  $line[]=_csv('Normal');  
  $line[]=_csv('2');
  $theline=join(',',$line);
  print($theline);
  print("\r\n");
}
?>