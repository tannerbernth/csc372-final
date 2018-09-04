<?php

// READS DOCX FROM GIVEN FILENAME STRING, RETURNS STRING WITH XML TAGS REMOVED 
function read_docx($filename){
    
    // get docx data and store as $content
    $parsed_content = '';
    $content = '';

    if(!$filename || !file_exists($filename)) return false;
    $zip = zip_open($filename);
    if (!$zip || is_numeric($zip)) return false;
    while ($zip_entry = zip_read($zip)) {
        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
        if (zip_entry_name($zip_entry) != "word/document.xml") continue;
        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
        zip_entry_close($zip_entry);
    }
    zip_close($zip);
    
    // magic regex parsing
    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    $parsed_content = strip_tags($content);

    return $parsed_content;
}


// RETURNS SORTED ASSOCIATIVE ARRAY GIVEN PARSED STRING
function wordsToDict($words){
    $out = array_count_values(str_word_count($words, 1));
    $out = array_reverse($out, true);
    return $out;
}

// RETURNS STRING REPRESENTATION OF WORDS GIVEN ASSOCIATIVE ARRAY, FOR PRINTGRAPH()
function keysToString($array){
    $keys = array_keys($array);
    $out = "[";
    for($i=0; $i<10; $i++){
        $out .= "'" . $keys[$i] . "'" . ", ";
    }
    $out = substr($out, 0 , -2) . "]";
    return $out;
}

// RETURNS STRING REPRESENTATION OF NUMBER OF WORDS GIVEN ASSOCIATIVE ARRAY, FOR PRINTGRAPH()
function valuesToString($array){
    $values = array_values($array);
    $out = "[";
    for($i=0; $i<10; $i++){
        $out .= $values[$i] . ", ";
    }
    $out = substr($out, 0 , -2) . "]";
    return $out;
}

// PRINTS GRAPH GIVEN SORTED ASSOCIATIVE ARRAY, HTML FILE MUST CONTAIN CANVAS! ADD CANVAS VARIABLE FOR MULTIPLE CANVI
function printGraph($sorted){ ?>

	<script>

	var barChartData = {
		labels : <?php echo keysToString($sorted); ?>,
		datasets : [
			{
				fillColor : "rgba(255,130,0, 1)",
				strokeColor : "rgba(200,90,0, 1)",
				highlightFill: "rgba(255,130,0, 1)",
				highlightStroke: "rgba(0,0,0, 1)",
				data : <?php echo valuesToString($sorted); ?>
			}
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}

	</script>

	
        <div>
			<canvas id="canvas" height="300" width="600"></canvas>
		</div> 
    </div>
<?php } 

// PRINTS GRAPH GIVEN SORTED ASSOCIATIVE ARRAY, HTML FILE MUST CONTAIN CANVAS! ADD CANVAS VARIABLE FOR MULTIPLE CANVI
function printTotals($sorted){ ?>
	<div class="graph">
  <div class="headerLabel">Highest Frequency Of a Single Word In a Document</div>
	<script>

	var barChartData = {
		labels : <?php echo keysToString($sorted); ?>,
		datasets : [
			{
				fillColor : "rgba(255,130,0, 1)",
				strokeColor : "rgba(200,90,0, 1)",
				highlightFill: "rgba(255,130,0, 1)",
				highlightStroke: "rgba(0,0,0, 1)",
				data : <?php echo valuesToString($sorted); ?>
			}
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("canvas-two").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}

	</script>

	<div>
		<canvas id="canvas-two" height="300" width="600"></canvas>
	</div>
  </div>
<?php } 


// STORE ASSOCIATIVE ARRAY IN DATABASE GIVEN USERNAME AND UNSERIALIZED ARRAY
function storeArray($name, $array, $file_name, $file_size, $file_type, $today){

    $serialized = serialize($array);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "final";

    try {
    	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    // set the PDO error mode to exception
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
    	// prepare sql and bind parameters
   		$stmt = $conn->prepare("INSERT INTO data (username, array, filename, filesize, filetype, date) VALUES (:first, :second, :third, :fourth, :fifth, :sixth)");
    	$stmt->bindParam(':first', $name);
    	$stmt->bindParam(':second', $serialized);
    	$stmt->bindParam(':third', $file_name);
    	$stmt->bindParam(':fourth', $file_size);
    	$stmt->bindParam(':fifth', $file_type);
    	$stmt->bindParam(':sixth', $today);

    	// insert the data
   		$stmt->execute();

    	}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
    	}
		$conn = null;

}


// RETURNS ASSOCIATIVE ARRAYS FROM DATABASE GIVEN USERNAME
function retrieveArray($name){

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "final";

    try {
    	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    // set the PDO error mode to exception
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
    	// prepare sql and bind parameters
   		$stmt = $conn->prepare("SELECT array FROM data WHERE username = :first");
    	$stmt->bindParam(':first', $name);

    	// retrieve the data
   		$stmt->execute();

		// create and populate output array
		$array = array();
    	foreach( $stmt as $row ) {
		    $array[] = unserialize($row['array']);
		}

    	}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
    	}
		$conn = null;
		
		return $array;
}

//RETURNS THE NAME OF THE FILE FROM THE DATABASE
function retrieveFileName($array){

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "final";

    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      // prepare sql and bind parameters
      $stmt = $conn->prepare("SELECT fileName FROM data WHERE array = :array");
      $stmt->bindParam(':array', serialize($array));

      // retrieve the data
      $stmt->execute();
      $fileName = $stmt->fetchColumn();

      }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    $conn = null;
    
    return $fileName;
}

// RETURNS ASSOCIATIVE ARRAY OF ALL USER SUBMITTED DATA
function getAggregate(){
	$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "final";

    try {
    	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    // set the PDO error mode to exception
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
    	// prepare sql and bind parameters
   		$stmt = $conn->prepare("SELECT array FROM data");

    	// retrieve the data
   		$stmt->execute();

		// create and populate output array
		$array = array();
    	foreach( $stmt as $row ) {
		    $array = array_merge($array, unserialize($row['array']));
		}

    	}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
    	}
		$conn = null;
		
		arsort($array);

		return $array;
	
}

// USES $_FILE INFO TO ADD INFO TO DATABASE AND PRINT GRAPH
function parseUpload(){
	   if(isset($_FILES['doc'])){
      $errors= array();
      $file_name = $_FILES['doc']['name'];
      $file_size =$_FILES['doc']['size'];
      $file_tmp =$_FILES['doc']['tmp_name'];
      $file_type=$_FILES['doc']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['doc']['name'])));
      $today = date("F j, Y, g:i a"); 
      
      $accepted_extensions= array("pdf","docx");
      
      $uploadStatus = ($file_size < 2000000 && in_array($file_ext,$accepted_extensions));

      if($uploadStatus){
         move_uploaded_file($file_tmp,"uploads/".$file_name);
      }
      else{
         echo '<div style="background: rgba(250, 250, 250,1);
  border: 1px solid rgba(0,0,0,.15);
  border-radius: 3px;
  box-shadow: 0px 0px 3px rgba(0,0,0,.05);
  line-height: 15px;
  margin: 20px 0px 20px 0px;
  padding: 10px;
  text-align: center;
  width: 100%;">An error occurred. Only PDF and DOCX files less than 2MB are allowed.</div>';
      }

   }

   if(isset($_FILES['doc']) && $uploadStatus){ ?>
<div class="graph1">
<div class="headerLabel"> Statistics for file: <?php echo $_FILES['doc']['name'];  ?></div>
File Size: <?php echo $_FILES['doc']['size']; ?> <br>
File Type: <?php echo $_FILES['doc']['type']; ?> <br>
Upload Date: <?php echo date("F j, Y, g:i a") ?> <br>
<a class="goGraph" href="./index.php?file=<?= $_FILES['doc']['name']; ?>">View Chart</a>
		<?php 
		      $words = read_docx("uploads/".$_FILES['doc']['name']);
                      $unsorted = wordsToDict($words);
		      asort($unsorted, SORT_NUMERIC);
		      $sorted = array_reverse($unsorted, true);
		?>
        

	<?php 
	storeArray($_SESSION["username"], $sorted, $file_name, $file_size, $file_type, $today);
	
	}
}

// RETURN FILE'S INFO AS ASSOCIATIVE ARRAY FROM DATABASE GIVEN FILE NAME
function retrieveFileArray($name){

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "final";

    try {
    	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    // set the PDO error mode to exception
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
    	// prepare sql and bind parameters
   		$stmt = $conn->prepare("SELECT * FROM data WHERE filename = :first");
    	$stmt->bindParam(':first', $name);

    	// retrieve the data
   		$stmt->execute();

		// store the row
		$row = $stmt->fetch();

    	}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
    	}
		$conn = null;

		return $row;
}


// PRINTS GRAPH GIVEN SORTED ASSOCIATIVE ARRAY, HTML FILE MUST CONTAIN CANVAS! ADD CANVAS VARIABLE FOR MULTIPLE CANVI
function printFileArray($array){ ?>
<div class="graph1">
<div class="headerLabel"> Statistics for file: <?php echo $array['filename'];  ?></div>
File Size: <?php echo $array['filename']; ?> <br>
File Type: <?php echo $array['filesize']; ?> <br>
Upload Date: <?php echo $array['date'];?> <br>
		<?php printGraph(unserialize($array['array']));
}  
?>
