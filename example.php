<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "html-jpg-parser.php";

if (@$_POST['sitecont']) {
	$unedited_contents = $_POST['sitecont'];
	$image_edited_contents = htmlImagesToFilesAndResize($unedited_contents, 640,480);
	echo $image_edited_contents;
}

?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8" />
		<title>HTML - JPG - Parser example</title>
		<script>
		function subm() {
			document.getElementById("sitecont").value = document.getElementById("contents").innerHTML;
			document.getElementById("contform").submit();
		}
		</script>
	</head>
	<body>
		<form id="contform" action="example.php" method="post">
		<input type="hidden" name="sitecont" id="sitecont">
		<button onclick="subm()" value="submit">Submit</button>
		</form>

		<div id="contents" style="min-width:100px;min-height:100px;background:#aaa;" contenteditable>
			Add jpg image here (drag&drop) and click Submit
		</div>
	</body>
</html>
