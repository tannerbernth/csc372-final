<?php
require_once("./config.php"); 
require_once("functions.php");
if (isset($_SESSION["changePass"])) {
	echo $_SESSION["changePass"];
	unset($_SESSION["changePass"]);
}
if (isset($_POST["changePass"])) {
	$change = new config();
	$username = trim(htmlspecialchars($_SESSION["username"]));
	$password = htmlspecialchars($_POST["password"]);
	$newPassword = htmlspecialchars($_POST["confirmpassword"]);
	if ($_POST["confirmpassword"] != "" && $_POST["password"] != "") {
		if ($change->verified($username,$password)){
			if (strlen($newPassword) >= 8) {
				$change->changePassword($username,$newPassword);
				$_SESSION["changePass"] = '<div id="errorConfirm">Password changed successfully!</div>';
				header ("Location: index.php?mode=account");
			} else {
				$_SESSION["changePass"] = '<div id="errorConfirm">New password must be at least 8 characters long!</div>';
				header ("Location: index.php?mode=account");
			}
		} else {
			$_SESSION["changePass"] = '<div id="errorConfirm">Password was entered incorrectly!</div>';
			header ("Location: index.php?mode=account");
		}
	} else {
		$_SESSION["changePass"] = '<div id="errorConfirm">Please enter your password and your new password!</div>';
		header ("Location: index.php?mode=account");
	}
}
$userInfo = new config();
$filesUploaded = $userInfo->getFiles($_SESSION["username"]);
?>
<div id="formContainer">
	<div class="input">
		<div class="headerLabel"><?= $_SESSION["username"]; ?></div>
	</div>
	<div class="input">
		<div class="formLabel" id="changePassHeader">Change Password</div>
	</div>
	<form action="" method="post" id="changePass">
	<div class="input">
		<div class="formLabel">Current password</div>
		<div class="inputField"><input type="password" id="password" name="password" value=""></div>
		<div id="passLength" class="errorBox"></div>
	</div>
	<div class="input">
		<div class="formLabel">New Password</div>
		<div class="inputField"><input type="password" id="confirmPassword" name="confirmpassword" value=""></div>
		<div id="confirmPassLength" class="errorBox"></div>
	</div>
	<div class="formSubmit">
		<input type="submit" value="Save" name="changePass" id="regSubmit">
	</div>
	</form>
</div>
<script src="./script.js"></script>
<div class="account">
	<div class="input">
		<div class="headerLabel">Uploads</div>
	</div>
	<div class="input">
		<div class="formLabel">Files Uploaded</div>
		<div class="list">
			<ul>
			<?php 
			$userFiles = retrieveArray($_SESSION["username"]);
			if (sizeOf($userFiles) == 0) {
				echo "No files uploaded";
			}
			foreach($userFiles as $file) {
			$fileName = retrieveFileName($file);
			?>
			<a href="index.php?file=<?= $fileName; ?>"><?= $fileName; ?></a><br>
			<?php 
			} 
			unset($file);
			?>
			</ul>
		</div>
	</div>
</div>