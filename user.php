<?php
class User {
	var $id;
	var $username;
	var $passwd;
	var $email;
	var $displayName;
	var $created;
	var $lastSeen;
	var $authed;

	var $locations;

	function User() {
		$this->id = -1;
		$this->authed = false;
	}

	function getUser($id,$username="") {
		$sql = "SELECT * FROM users WHERE id = $id";

		if($username != "") {
			$sql = "SELECT * FROM users WHERE username = '$username'";
		}

		$res = mysql_query($sql);
		
		if($res == NULL) {
			return NULL;
		}

		$row = mysql_fetch_assoc($res);

		if($row == NULL) {
			return NULL;
		}

		$this->id 			= 	$row['id'];
		$this->username 	= 	$row['username'];
		$this->passwd   	= 	$row['passwd'];
		$this->email    	= 	$row['email'];
		$this->displayName	=	$row['display_name'];
		$this->created		= 	$row['created'];
		$this->lastSeen		= 	$row['last_seen'];
		$this->authed		= 	$row['authed'];

		$location = new Location();
		$this->locations = $location->getUserLocations($id);

		return true;
	}

	function login($username, $password) {
		if($this->getUser(-1,$username) === NULL) {
			die("No such username");
		}

		if($username != "" &&
		   $password != "" &&
		   $username == $this->username &&
		   md5($password) == $this->password) {
			$user->authed = true;
			$_SESSION['user_id'] = $this->id;
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
		} else {
			die("Login failed");
		}
	}

	function isAuthed() {
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
}

?>
