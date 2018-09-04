<?php 
require_once("./config.php"); 
if (isset($_SESSION["register"])) {
	echo $_SESSION["register"];
	unset($_SESSION["register"]);
}
if (isset($_POST["register"])) {
	$register = new config();
	$username = trim(htmlspecialchars($_POST["username"]));
	$password = htmlspecialchars($_POST["password"]);
	$confirmPassword = htmlspecialchars($_POST["confirmpassword"]);
	if (strLen($username) < 1) {
		$_SESSION["register"] = '<div id="errorConfirm">Username must be 1 character<br>or longer! (Max 20)</div>';
		header ("Location: index.php?mode=register");
	} else if (strlen($password) < 8) {
		$_SESSION["register"] = '<div id="errorConfirm">Password must be 8 characters<br>or longer!</div>';
		header ("Location: index.php?mode=register");
	} else if ($confirmPassword !== $password) {
		$_SESSION["register"] = '<div id="errorConfirm">You have re-entered your password incorrectly!</div>';
		header ("Location: index.php?mode=register");
	} else {
		if ($register->register($username,$password)) {
			$_SESSION["register"] = '<div id="errorConfirm">Registered successfully! Please log in to continue.</div>';
			header ("Location: index.php?mode=login");
		} else {
			$_SESSION["register"] = '<div id="errorConfirm">Username already exists!</div>';
			header ("Location: index.php?mode=register");
		}
	}
}
?>
<div id="unfilled"></div>
<div id="formContainer">
	<form action="" method="post" id="newMember">
	<div class="input">
		<div class="formLabel">Username</div>
		<div class="inputField"><input type="text" id="username" name="username" value="" maxlength="25"></div>
		<div id="userLength" class="errorBox"></div>
	</div>
	<div class="input">
		<div class="formLabel">Password</div>
		<div class="inputField"><input type="password" id="password" name="password" value=""></div>
		<div id="passLength" class="errorBox"></div>
	</div>
	<div class="input">
		<div class="formLabel">Confirm Password</div>
		<div class="inputField"><input type="password" id="confirmPassword" name="confirmpassword" value=""></div>
		<div id="confirmPassLength" class="errorBox"></div>
	</div>
	<div class="formSubmit">
		<input type="submit" value="Register" name="register">
	</div>
	</form>
</div>
<div class="formMessage">Returning member? Login <a href="./index.php?mode=login">here</a>!</div>
<script src="./script.js"></script>