<div id="userbar">
		<div id="nav">
			<a href="./index.php">
			<div id="title">
				<h1>ESSAY STATS</h1>
			</div>
			</a>
		</div>
		<?php 
		if (!isset($_SESSION["username"])) { 
		?>
		<div class="login">
			<a href="?mode=register">
				<div class="navbutton">
				Register
				</div>
			</a>
			<a href="?mode=login">
				<div class="navbutton">
				Login
				</div>
			</a>
		</div>
		<?php 
		} else { 
		?>
		<div class="login">
			<a href="./index.php?mode=logout">
				<div class="navbutton">
				Logout
				</div>
			</a>
			<a href="./index.php?mode=upload">
				<div class="navbutton">
				Upload
				</div>
			</a>
			<a href="./index.php?mode=account">
				<div class="navbutton">
				<span><?= $_SESSION["username"]; ?></span>
				</div>
			</a>
		</div>
		<?php 
		}
		?>
	</div>
	<div id="content">