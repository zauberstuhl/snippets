<?php
$url = 'edit.php';
$main_dir = '/srv/http/webapps';
$flist = directoryToArray($main_dir);

// check if form has been submitted
if (isset($_POST['text']) && isset($_POST['file'])) {
	// save the text contents
	file_put_contents($_POST['file'], $_POST['text']);

	// redirect to form again
	header(sprintf('Location: %s', $url));
	printf('<a href="%s">Moved</a>.', htmlspecialchars($url));
	
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">File Editor</h3>
			</div>
			<div class="panel-body">
				<?php
				if (isset($_GET['i'])) {
					$text = file_get_contents($flist[$_GET['i']]);
				?>
				<form action="" method="post">
					<textarea class="form-control" style="width:100%;height:250px;" name="text"><?php echo htmlspecialchars($text) ?></textarea>
					<h5>Filename:</h5>
					<input class="form-control" style="width:100%" type="text" name="file" value="<?php print $flist[$_GET['i']] ?>" />
					<div class="input-group">
						<input class="btn btn-default" type="submit" value="Save" />
						<input class="btn btn-default" type="reset" value="Reset" />
					</div>
				</form>
				<?php
				} else {
					print "<div class=\"list-group\">";
					for ($i = 0; $i < count($flist); ++$i) {
						if (preg_match('/\.md$/i', $flist[$i])) {
							print  "<a href=\"?i=$i\" class=\"list-group-item\">".$flist[$i]."</a>";
						}
					}
					print "</div>";
				}
				?>
			</div>
			<div class="panel-footer">
				<button class="btn" onclick="window.history.back();">Go back</button>
			</div>
		</div>
	</body>
</html>
<?php
function directoryToArray($directory, $extension="", $full_path = true) {
	$array_items = array();
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($directory. "/" . $file)) {
					$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $extension, $full_path)); 
				}
				else { 
					if(!$extension || (ereg("." . $extension, $file)))
					{
						if($full_path) {
							$array_items[] = $directory . "/" . $file;
						}
						else {
							$array_items[] = $file;
						}
					}
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}
?>
