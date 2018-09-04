<?php 
session_start();
echo "<title>Essay Stats</title>";
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="./style.css">
<link rel="shortcut icon" href="./favicon.ico">
</head>
<body>
<div id="container">
	<?php 
	require_once("./userbar.php");
	if (isset($_GET["mode"])) {
		if ($_GET["mode"] == "login" && !isset($_SESSION["username"])) require_once("./login.php");
		else if ($_GET["mode"] == "register" && !isset($_SESSION["username"])) require_once("./register.php");
		else if ($_GET["mode"] == "logout") require_once("./logout.php");
		else if ($_GET["mode"] == "account" && isset($_SESSION["username"])) require_once("./account.php");
		else if ($_GET["mode"] == "upload" && isset($_SESSION["username"])) require_once("./upload.php");
		else header ("Location: index.php");
	} else if (isset($_GET["file"])) {
		require_once("./view.php");
	} else {
		require_once("./indexContent.php");
		require_once("functions.php");
		printTotals(getAggregate());
	}
	require_once("./footer.php");
	?>
</div>
<script src="Chart.js"></script>
</body>
</html>