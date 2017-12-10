<?php
require_once __DIR__ . '/quickstart.php';

// gets the next ten upcoming events and tries to update
$event_summary = $_GET["robot_name"] . " on";
$date_end = new DateTime("+" . $_GET["timeout"] . " seconds");
$calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);

// creates an event with a given summary and end time
function create_event($summary, $end, $service) {
	$event = new Google_Service_Calendar_Event(array(
		'summary' => $summary,
		'location' => 'GDC',
		'description' => 'Session with the robot.',
		'start' => array(
			'dateTime' => date("c", time()),
			'timeZone' => 'America/Los_Angeles',
		),
		'end' => array(
			'dateTime' => $end->format("c"),
			'timeZone' => 'America/Los_Angeles',
		)
	));

	$calendarId = 'primary';
	$event = $service->events->insert($calendarId, $event);
	// print the new event
	printf('Event created: %s\n', $event->htmlLink);
}

// updates an event with a given end time
function update_event($event, $new_end, $service) {
	$event->end->setDateTime($new_end->format("c"));
	$updatedEvent = $service->events->update('primary', $event->getId(), $event);
	// print the updated date.
	printf("New end: %s\n", $updatedEvent->getUpdated());
}

if (count($results->getItems()) == 0) {
	print "No upcoming events found.\n";
	create_event($event_summary, $date_end, $service);
} else {
	print "Upcoming events:\n";
	$found_on = false;
	foreach ($results->getItems() as $event) {
		$start = $event->start->dateTime;
		if (empty($start)) {
			$start = $event->start->date;
		}
		printf("\n%s (%s)\n", $event->getSummary(), $start);
		if($event->getSummary() == $event_summary) {
			printf("%s is currently on\n", $_GET["robot_name"]);
			update_event($event, $date_end, $service);
			$found_on = true;
		}
	}
	if(!$found_on) {
		printf("%s is not on\n", $_GET["robot_name"]);
		create_event($event_summary, $date_end, $service);
	}
}
//echo $date_end->format("c");
?>
