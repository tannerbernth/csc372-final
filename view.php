<?php
require_once("functions.php");
// if uploading file, parse it
if(isset($_FILES['doc'])){
	parseUpload();
}
// if requesting a file, show it
else if(isset($_GET['file'])){
	printFileArray(retrieveFileArray($_GET['file']));
}

?>
<script src="Chart.js"></script>
