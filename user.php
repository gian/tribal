<?php
class User {
	var $id;
	var $username;
	var $passwd;
	var $email;
	var $display_name;
	var $created;
	var $last_seen;
	var $authed;

	var $locations;

	public function User() {
		$this->id = -1;
		$this->authed = false;
	}

	public static function getUser($id,$username="") {
		$sql = "SELECT * FROM users WHERE id = $id";

		if($username != "") {
			$sql = "SELECT * FROM users WHERE username = '$username'";
		}

		$res = mysql_query($sql);
		
		if($res == NULL) {
			return NULL;
		}

		$row = mysql_fetch_object($res,'User');

		$row->locations = Location::getUserLocations($id);

		return $row;
	}

	public static function login($username, $password) {
		$user = self::getUser(-1,$username);
		
		if($user === NULL) {
			die("No such username");
		}

		if($username != "" &&
		   $password != "" &&
		   $username == $user->username &&
		   md5($password) == $user->password) {
			$user->authed = true;
			$_SESSION['user_id'] = $user->id;
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
		} else {
			die("Login failed");
		}
	}

	public function isAuthed() {
		if(!isset($_SESSION['user_id'])) return false;

		$this->getUser($_SESSION['user_id']);

		if($_SESSION['username'] != "" &&
		   $_SESSION['password'] != "" &&
		   $_SESSION['username'] == $this->username &&
		   md5($_SESSION['password']) == $this->password) {
	
			$sql = "UPDATE users SET last_seen = now() WHERE " .
					"id = {$this->id}";

			mysql_query($sql);

			return true;
		}

		return false;
	}

	public function getTribes() {
		$sql = "SELECT tribe_id FROM user_tribes WHERE user_id = {$this->user_id}";
		$res = mysql_query($sql);

		$tribes = array();
		
		while(($row = mysql_fetch_assoc($res)) != NULL) {
			$tribes []= $row['tribe_id'];
		}

		return $tribes;
	}
}

?>
