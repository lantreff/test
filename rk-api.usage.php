<?php
error_reporting(E_ERROR);

// Include dependencies with composer
#require_once('../vendor/autoload.php');
require_once('../../yaml/lib/sfYamlParser.php');

// Include the API wrapper
require_once('../lib/runkeeperAPI.class.php');

/* API initialization */
$rkAPI = new RunKeeperAPI(
	__DIR__ . '/../config/rk-api.sample.yml'	/* api_conf_file */
);
if ($rkAPI->api_created == false) {
	echo 'error '.$rkAPI->api_last_error; /* api creation problem */
	exit();
}

/* Generate link to allow user to connect to Runkeeper and to allow your app*/
$linkUrl = $rkAPI->connectRunkeeperButtonUrl();
echo $linkUrl;

echo "<pre>";

/* After connecting to Runkeeper and allowing your app, user is redirected to redirect_uri param (as specified in YAML config file) with $_GET parameter "code" */
if ($_GET['code']) {
	$auth_code = $_GET['code'];
	if ($rkAPI->getRunkeeperToken($auth_code) == false) {
		echo $rkAPI->api_last_error; /* get access token problem */
		exit();
	}
	else {
echo $rkAPI->access_token."<=>".$rkAPI->token_type."<hr>";
		/* Your code to store $rkAPI->access_token (client-side, server-side or session-side) */
		/* Note: $rkAPI->access_token will have to be set and valid for following operations */

		/* Do a "Read" request on "Profile" interface => return all fields available for this Interface */
		$rkProfile = $rkAPI->doRunkeeperRequest('Profile','Read');
		print_r($rkProfile);

#		/* Do a "Read" request on "Settings" interface => return all fields available for this Interface */
#		$rkSettings = $rkAPI->doRunkeeperRequest('Settings','Read');
#		print_r($rkSettings);
#
#		/* Do a "Read" request on "FitnessActivityFeed" interface => return all fields available for this Interface or false if request fails */
		$rkActivities = $rkAPI->doRunkeeperRequest('FitnessActivityFeed','Read');
		if ($rkActivities) {
			print_r($rkActivities);
		}
		else {
			echo $rkAPI->api_last_error;
			print_r($rkAPI->api_request_log);
		}
#
#		/* Do a "Read" request on "FitnessActivities" interface => return all fields available for this Interface or false if request fails */
#		/* More than likely requires an activity url from the previous request */
		$rkActivities = $rkAPI->doRunkeeperRequest('FitnessActivity','Read',null,'/fitnessActivities/280860722');
		if ($rkActivities) {
			print_r($rkActivities);
		}
		else {
			echo $rkAPI->api_last_error;
			print_r($rkAPI->api_request_log);
		}

		// Uncomment to:
		/* Do a "Create" request on "FitnessActivity" interface with fields => return created FitnessActivity content if request success, false if not */
#		$fields = json_decode('{"type": "Running", "equipment": "None", "start_time": "Sat, 1 Jan 2011 00:00:00", "notes": "My first late-night run", "path": [{"timestamp":0, "altitude":0, "longitude":-70.95182336425782, "latitude":42.312620297384676, "type":"start"}, {"timestamp":8, "altitude":0, "longitude":-70.95255292510987, "latitude":42.31230294498018, "type":"end"}], "post_to_facebook": true, "post_to_twitter": true}');
#		$fields = json_decode('{"type": "Running", "equipment": "None", "start_time": "Sat, 1 Jan 2011 00:00:00", "notes": "My first late-night run", "path": [{"timestamp":0, "altitude":0, "longitude":-70.95182336425782, "latitude":42.312620297384676, "type":"start"}, {"timestamp":8, "altitude":0, "longitude":-70.95255292510987, "latitude":42.31230294498018, "type":"end"}]}');
//$date = "Tue, 27 Sep 2013";
$fields = array(
  array(
    "type" => "Swimming",
    "equipment" => "None",
    "start_time" => "Fri, 13 Dec 2013 15:30:00",
    "notes" => "",
    "total_distance" => 1500,
    "duration" => (30*60),
    "has_path" => false
  ),
  array(
    "type" => "Swimming",
    "equipment" => "None",
    "start_time" => "Tue, 3 Dec 2013 18:30:00",
    "notes" => "",
    "total_distance" => 1500,
    "duration" => (30*60),
    "has_path" => false
  ),
#  array(
#    "type" => "Swimming",
#    "equipment" => "None",
#    "start_time" => "Tue, 17 Dec 2013 18:30:00",
#    "notes" => "",
#    "total_distance" => 1500,
#    "duration" => (30*60),
#    "has_path" => false
#  ),
);
//foreach($fields as $field) $rkCreateActivity = $rkAPI->doRunkeeperRequest('NewFitnessActivity','Create',$field);
#$fields = array(
#  "type" => "Cycling",
#  "equipment" => "None",
##  "start_time" => "2013-08-05 18:00:00 GMT",
#  "start_time" => "$date 13:50:00",
#  "notes" => "",
#  "total_distance" => 5000,
#  "duration" => (15*60),
#  "has_path" => false
#);
#$rkCreateActivity = $rkAPI->doRunkeeperRequest('NewFitnessActivity','Create',$fields);
#$fields = array(
#  "type" => "Rowing",
#  "equipment" => "None",
##  "start_time" => "2013-08-05 18:00:00 GMT",
#  "start_time" => "$date 14:00:00",
#  "notes" => "",
#  "total_distance" => 12000,
#  "duration" => (80*60),
#  "has_path" => false
#);
#$rkCreateActivity = $rkAPI->doRunkeeperRequest('NewFitnessActivity','Create',$fields);
#$fields = array(
#  "type" => "Cycling",
#  "equipment" => "None",
##  "start_time" => "2013-08-05 18:00:00 GMT",
#  "start_time" => "$date 16:00:00",
#  "notes" => "",
#  "total_distance" => 5000,
#  "duration" => (15*60),
#  "has_path" => false
#);
#$rkCreateActivity = $rkAPI->doRunkeeperRequest('NewFitnessActivity','Create',$fields);
#		if ($rkCreateActivity) {
#			print_r($rkCreateActivity);
#		}
#		else {
			echo $rkAPI->api_last_error;
			print_r($rkAPI->api_request_log);
#		}
#
	}
}
echo "</pre>";
?>
