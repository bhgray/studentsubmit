<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<body>
<!--  BEGIN PHP CODE -->
<?php
	$debug = 'true';
	$file = 'courses.php';
	include $file;
	$xml = new SimpleXMLElement($xmlstr);
?>
<form action="submit2.php" method="post" enctype="multipart/form-data">
  <p align="center"><strong>Assignment Submission Web page.</strong></p>
  <p align="left"><strong>Instructions:</strong></p>
  <ol>
	<li>This is step 1 of 4.</li>
	<li>Select the name of your class from the drop down list.</li>
	<li>Press "Continue" -- the page will refresh to allow you to submit your files.</li>
  </ol>
  <p>Lab Name:</p>
  <select name='courseName'>
	<?php
  	foreach ($xml->course as $course) 
	{
		echo "<option" . " value=\"" . $course->id . "\">" . $course->id . ":  " . $course->name . "</option>";
	}
	
	?>
	
  </select>
<br />
	<br />
	<input type="submit" value="Go to step 2" method="POST">
	  </p>
</form>
	</body>
</html>
