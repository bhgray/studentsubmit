<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="/resources/css/reset-html.css" media="all" charset="utf-8" />
		<link rel="stylesheet" href="/resources/css/main.css" media="all" charset="utf-8" />
		<title>Teachopensource :: APCS :: Submissions</title>
	</head>
	<body>
		<div id="wrap">
			<div id="header">
				<div id="header_text_box">
					<span id="header_left" class="site_title">
						Teachopensource
					</span>
					<span id="header_right" class="small_text">
						A site for students in Brent Gray's classes at the <br /> JR Masterman School in Philadelphia, PA
					</span>					
				</div>
			</div>
			<div id="body_layer">
				<div id="nav">
<!-- #bbinclude "top_nav.incl" -->
<ul>
	<li><a href="http://www.teachopensource.org">Home</a></li> |
	<li><a href="http://apcs.teachopensource.org">APCS</a></li> |
	<li><a href="http://precalculus.teachopensource.org">Precalc</a></li> |
	<li><a href="http://geometry.teachopensource.org">Geometry</a></li> |
	<li><a href="http://grades.teachopensource.org">Grades</a></li>	
</ul>
<!-- end bbinclude -->
				</div>
				<div id="sidebar">
					<div id="sidebar_text_box">
<!-- #bbinclude "apcs_nav.incl" -->
<div id="button">
<ul>
	<li><a href="http://apcs.teachopensource.org/syllabus.html">Syllabus</a></li>
	<li><a href="http://apcs.teachopensource.org/labs.html">Labs</a></li>	
	<li><a href="http://apcs.teachopensource.org/policies.html">Policies</a></li>
	<li><a href="http://apcs.teachopensource.org/resources.html">Resources</a></li>
	<li><a href="http://apcs.teachopensource.org/submit.php">Submissions</a></li>	
</ul>
</div>
<!-- end bbinclude -->
					</div>
				</div>
				<div id="main">
					<div id="main_text_box">

<!--  BEGIN PHP CODE -->

<p align="center"><strong>Lab Submission Web Page</strong></p>
<?php 
	$debug = false;
	$filename = $_POST['filename'];
	include "../resources/php/" . $filename;

	$labName = $_POST['labName'];
	$labFiles = $labFiles[$labName];
	$studentID = $_POST['studentName'];
	$studentInfoArray = $studentNames[$studentID];
	$studentName = $studentID . "-" . $studentInfoArray[0] . "-" . $studentInfoArray[1] . "-" . $studentInfoArray[2];
	$studentEmail = $studentInfoArray[2];
	
	if ($debug) {
		echo "<b>Debug Info</b><br />";
		echo "File:  " . $filename;
		echo "<br />studentEmail:  " . $studentEmail;
	}
 ?>
<p align="center"><strong><?php echo $class ?>

<p align="left"><strong>Step 2 instructions:</strong></p>
<form action="submit3.php" method="post" enctype="multipart/form-data">

	<?php
		echo "<input type='hidden' name='filename' value='";
		echo $filename . "' />";
	?>
	<ol type="1">
		<li>For each required file, select the &quot;Choose File&quot; button, and a dialog box will allow you to select the appropriate location.</li>
		<li>Select the &quot;Send Files&quot; button. </li>
		<li>A receipt will appear, giving you the time and date of your submission.</li>
		<li>You may submit multiple times -- each will overwrite the other. </li>
	</ol>
	<p>Student Name:  <strong><?php echo $studentName ?></strong></p>
	<p>Student Email Address:  <strong><?php echo $studentEmail ?></strong></p>
	<p>Lab Name: <strong><?php echo $_POST['labName'] ?> </strong></p>
	<p><strong>Files to submit:</strong><br />
	
	<input name='labName' type='hidden' value="<?php echo $labName ?>" >
	<input name='studentName' type='hidden' value="<?php echo $studentName ?>" >
	<input name='studentEmail' type='hidden' value="<?php echo $studentEmail ?>" >
	<table border="1">
		<tr bgcolor="#6666FF">
			<th>
				File Number
			</th>
			<th>
				File Name
			</th>
			<th>
				Choose File Here
			</th>
		</tr>
			
		
		<?php
			while (list($key, $val) = each($labFiles)) {
				echo "<tr>";
				echo "<td>" . ($key+1) . "</td><td>" . $val . "</td>";
				echo "<td><input name='userfile[]' type='file'></td>";
				echo "</tr>";
			}		
		?>
	</table>
	<br />
	<input type="submit" value="Send files" method="POST">
</form>

<!--  END PHP CODE -->

			</div>
		</div>
	</body>
</html>
