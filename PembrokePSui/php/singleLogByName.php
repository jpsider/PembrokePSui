<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->

<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>PembrokePS Logfile:</h3>
		<?php
			$findstr = 'xml';
			$pos = strpos($Log_Path, $findstr);
			if ($pos === false) {
				$myfile = fopen("$Log_Path", "r") or die("Unable to open //file!");
				$pageText = fread($myfile,filesize("$Log_Path"));
				echo '<table class="table-compact"><tr><td>'. nl2br($pageText) .'</td></tr></table>';
				fclose($myfile);
			}
			else {
				//header('Content-type: text/xml');
				//echo "<a href='singleImage.php?Log_Path=$Log_Path' target='_blank'>View Image</a>";
				//echo "<pre class='prettyprint linenums'>";
				//	echo "<code class='language-xml'>";
				//	htmlspecialchars(file_get_contents("$Log_Path"), ENT_QUOTES); 
				//echo "</code></pre>";
				//$xml = simplexml_load_string($Log_Path);
				//echo $xml->asXML();
				$XML = file_get_contents($Log_Path);
				$XML = str_replace('&', '&amp;', $XML);
				$XML = str_replace('<', '&lt;', $XML);
				echo '<pre>' . $XML . '</pre>';
			}
		?>
	</div><!-- End content-area -->
    <nav class="sidenav">
		<?php
			require_once 'components/Side_Bar.html';
		?>
	</nav>
</div><!-- End content-container (From Header) -->
</body>
<!-- Insert if there is Head PHP -->
<!-- End Head PHP closing statement -->
</html>