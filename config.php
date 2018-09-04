<?php
class config {
	private $DB;
	public function __construct() {
	  $db = "mysql:dbname=final;host=127.0.0.1";
	  $user = "root";
	  $password = "";
  	  try {
		$this->DB = new PDO ( $db, $user, $password );
		$this->DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	  } catch ( PDOException $e ) {
	       echo ("Error establishing Connection");
	       exit ();
	  }
	}
	public function register($username, $password) {
		$exists = false;
		$stmt = $this->DB->prepare("SELECT username FROM users");
		$stmt->execute();
		$userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (sizeOf($userInfo) > 0) {
			foreach ($userInfo as $users) {
				if (strtolower($username) == strtolower($users["username"])) {
					$exists = true;
				}
			}
		}
		if (!$exists) {
			$salt = openssl_random_pseudo_bytes(8,$cstong);
			$saltedPass = $salt.$password;
			$password = password_hash($saltedPass,PASSWORD_DEFAULT);
			$stmt = $this->DB->prepare("INSERT INTO users (username, hash, salt) VALUES (:username, :password, :salt)");
			$stmt->bindParam(':salt',$salt);
			$stmt->bindParam(':username',$username);
			$stmt->bindParam(':password',$password);
			$stmt->execute();
			return true;
		} else {
			return false;
		}
	}
	public function verified($username, $password) {
		$stmt = $this->DB->prepare("SELECT salt FROM users WHERE username=?");
		$stmt->execute(array($username));
		$salt = $stmt->fetchColumn();
		$password = $salt.$password;

		$stmt = $this->DB->prepare("SELECT hash FROM users WHERE username=?");
		$stmt->execute(array($username));
		$hash = $stmt->fetchColumn();
		if (password_verify($password,$hash)) {
			return TRUE;
		} else {
			return FALSE;
		}
   	}
   	public function getUsername($username) {
   		$stmt = $this->DB->prepare("SELECT username FROM users WHERE username=?");
		$stmt->execute(array($username));
		$username = $stmt->fetchColumn();
		return $username;
   	}
   	public function getFiles($username) {
   		$stmt = $this->DB->prepare("SELECT files FROM users WHERE username=?");
		$stmt->execute(array($username));
		$files = $stmt->fetchColumn();
		return $files;
   	}
   	public function getSalt($username) {
   		$stmt = $this->DB->prepare("SELECT salt FROM users WHERE username=?");
		$stmt->execute(array($username));
		$salt = $stmt->fetchColumn();
		return $salt;
   	}
   	public function changePassword($username,$newPassword) {
   		$newPassword = $this->getSalt($username).$newPassword;
   		$newPassword = password_hash($newPassword,PASSWORD_DEFAULT);
   		$stmt = $this->DB->prepare("UPDATE users SET hash=:newPass WHERE username=:username");
   		$stmt->bindParam(":newPass",$newPassword);
   		$stmt->bindParam(":username",$username);
   		$stmt->execute();
   		return true;
   	}
} 
?>