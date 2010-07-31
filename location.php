<?php
class Location {
	var $id;
	var $user_id;
	var $lat;
	var $lng;
	var $street_address;
	var $city;
	var $post_code;
	var $country;
	var $update_method;
	var $last_seen;


	public function Location() {
		$this->id = -1;
		$this->user_id = -1;
	}

	public static function getUserLocations($user_id) {
		$locations = array();

		$sql = "SELECT * FROM locations WHERE user_id = $user_id " .
				"ORDER BY id DESC";
		$res = mysql_query($sql);

		if(!$res) {
			return array();
		}

		while(($row = mysql_fetch_object($res,'Location')) != NULL) {
			$locations []= $row;
		}

		return $locations;
	}

	public function getCurrentUserLocation($user_id) {
		$locations = self::getUserLocations($user_id);
		if(sizeof($locations) > 0) {
			return $locations[0];
		}
		else return false;
	}

	// Based on current location
	// withinTime in seconds
	// range in arc degrees
	public function getNearbyLocations($withinTime = 3600, $range = 0.5) {
		$users = array();
		
		$halfArc = $range/2.0;

		$sql = "SELECT * FROM locations " .
				"WHERE last_seen >= now()-$withinTime AND " .
				"(abs(lat - $lat) < $halfArc) AND " .
				"(abs(lng - $lng) < $halfArc) " .
				"ORDER BY ((abs(lat-$lat) + abs(lng-$lng)) / 2) ASC";

		$res = mysql_query($sql);

		while(($row = mysql_fetch_assoc($res)) != NULL) {
			print "Nearby:<br/>\n";
			print_r($row);
		}

		// TODO: Should make a generic row-to-object function.


	}

}
?>

