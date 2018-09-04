<?php 
if (isset($_SESSION["uploaded"])) {
	echo $_SESSION["uploaded"];
	unset($_SESSION["uploaded"]);
}
?>
<div id="formContainer">
<div class="input">
<div class="headerLabel">
<span>Upload a .pdf or .docx file to view your essay analysis!</span>
</div>
</div>
<div id="upload">
<form action="" method="POST" enctype="multipart/form-data" id="file">
	<div class="formSubmit">
    	<label for="doc">
    	<div>Choose File</div>
    	<input type="file" name="doc" id="doc">
    	</label>
    	<div id="fileName">&nbsp;</div>
    </div>
    <div class="formSubmit">
    <button type="button" onclick="send()">Upload</button>

    </div>
</form>
</div>
</div>
<div id="demo">

</div>
<script>
document.getElementById("doc").onchange = function () {
    document.getElementById("fileName").innerHTML = this.value;
};
function send() {
  var form = document.getElementById('file');
  var formData = new FormData(form);

  var xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      response = xhttp.responseText;
      document.getElementById("demo").innerHTML = response;
    }
  };
  xhttp.open("POST", "ajax.php", true);
  xhttp.send(formData);

}
</script>