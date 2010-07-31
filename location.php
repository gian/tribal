<?php
class Location {
	var $id;
	var $user_id;
	var $lat;
	var $long;
	var $streetAddress;
	var $city;
	var $postCode;
	var $country;
	var $updateMethod;
	var $lastSeen;


	function Location() {
		$this->id = -1;
		$this->user_id = -1;
	}

	function getUserLocations($user_id) {
		$locations = array();

		$sql = "SELECT * FROM locations WHERE user_id = $user_id " .
				"ORDER BY id DESC";
		$res = mysql_query($sql);

		if(!$res) {
			return array();
		}

		while(($row = mysql_fetch_assoc($res)) != NULL) {
			$l = new Location();
			$l->id				= $row['id'];
			$l->user_id			= $row['user_id'];
			$l->lat				= $row['lat'];
			$l->long			= $row['long'];
			$l->streetAddress	= $row['street_address'];
			$l->city			= $row['city'];
			$l->postCode		= $row['post_code'];
			$l->country			= $row['country'];
			$l->updateMethod	= $row['update_method'];
			$l->lastSeen		= $row['last_seen'];

			$locations []= $l;
		}

		return $locations;
	}

	function getCurrentUserLocation($user_id) {
		$locations = getUserLocations($user_id);
		if(sizeof($locations) > 0) {
			return $locations[0];
		}
		else return false;
	}

	// Based on current location
	// withinTime in seconds
	// range in arc degrees
	function getNearbyLocations($withinTime = 3600, $range = 0.5) {
		$users = array();
		
		$halfArc = $range/2.0;

		$sql = "SELECT * FROM locations " .
				"WHERE last_seen >= now()-$withinTime AND " .
				"(abs(lat - $lat) < $halfArc) AND " .
				"(abs(long - $long) < $halfArc) " .
				"ORDER BY ((abs(lat-$lat) + abs(long-$long)) / 2) ASC";

		$res = mysql_query($sql);

		while(($row = mysql_fetch_assoc($res)) != NULL) {
			print "Nearby:<br/>\n";
			print_r($row);
		}

		// TODO: Should make a generic row-to-object function.


	}

}
?>

