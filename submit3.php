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

	<?php
	
	$debug = false;
	function mkdir_r($dirName, $rights=0777){
		$debug = false;
		if ($debug) {
			echo "mkdir_r called on " . $dirName . "<br />";
		}
		
		$dirs = explode('/', $dirName);
		$dir='';
		foreach ($dirs as $part) {
			$dir = $dir . '/' . $part;
			if ($debug) {
				echo "mkdir_r creating " . $dir . "<br />";
			}		
			
			if (!is_dir($dir) && strlen($dir)>0)
				mkdir($dir, $rights);
		}
	}
	
	include "../resources/php/myMail.php";
 	include "../resources/php/" . $_POST['filename']; 

 	// variable inits 
 	$labName = $_POST['labName']; 
	$rootDir = '/users/home/bhgray/web/public/submit/' . $shortclassname . '/';
	$logFileName = 'submitLog.txt';
	$uploadDir = $rootDir . $labName;
	$studentDir = $uploadDir . '/' . $_POST['studentName'] . '/';
	$studentEmail = $_POST['studentEmail'];
	$dirpermission = 0777;
	$filepermission = 0777;
	$dateTimeFormatString = '%D:%R';
	$timeStampESTOffset = 0; // offset to compensate for local server time, if necessary

	// store the current umask permissions
	$old = umask(0);
	// set the permissions appropriately for this upload
	umask(0777 & ~$dirpermission);
	
	if(!file_exists($uploadDir)) {
		mkdir_r($uploadDir, $dirpermission);
	}
	
	if(!file_exists($studentDir)) {
		mkdir_r($studentDir, $dirpermission);
	}

	// debugging
	if ($debug){	
		echo $uploadDir . "<br />";
		echo $studentDir . "<br />";
		echo $dest . "<br />";
	}

	// get the number of files uploaded (by their temporary name)
	$numFiles = sizeof($_FILES['userfile']['tmp_name']);
	if ($debug) {
		echo $numFiles . '<br />';
	}

	// REMEMBER:  single quotes don't expand variables or escaped characters, double-quotes do
	// get the receipt string ready
	$shortReceiptString = "====================================================\n";
	$shortReceiptString = $shortReceiptString . "Lab Submission Report\nLab:  " . $labName . "\nSubmitted By:  ". $_POST['studentName'] . "\nFiles:  \n";
	$receiptString = "<html><body><hr />";
	$receiptString = $receiptString . "<strong>Lab Submission Report</strong><br /><strong>Lab:</strong>  " . $labName . "<br /><strong>Submitted by:</strong>  " . $_POST['studentName'] . "<br /><br /><strong>File listings:</strong><br />";

	// upload each file in turn
	for ($i = 0; $i < $numFiles; $i++) {
		$fileName = $_FILES['userfile']['name'][$i];
		$dest = $studentDir . $fileName;
		$file = $_FILES['userfile']['tmp_name'][$i];
		if ($debug) {
			echo 'file no.  ' . $i . '<br />';
			echo 'from:  ' . $file . '<br />';
			echo 'to:  ' . $dest . '<br />';
			echo '<br />';
		}
		
		// create the timestamp and java timestamp string
		// note that the server must be in CA b/c times are GMT -8
		$timestamp = time() + $timeStampESTOffset;		// offset 10,800 seconds (3 hrs) from CA time.
		$timeStampString = date("D M j G:i:s Y", $timestamp);
		if ($debug) { echo $fileName . "<br />"; }

		$filecontents = file_get_contents($file);
		$filecontents = "<pre><code>" . $filecontents . "</code></pre>";

		if(move_uploaded_file($file, $dest)) {
			$shortReceiptString = $shortReceiptString. $_FILES['userfile']['name'][$i] .  '  (' . $_FILES['userfile']['size'][$i] . ' bytes) on ' . $timeStampString;
			$receiptString = $receiptString . "<br /><hr /><p align='center'><strong>" . $_FILES['userfile']['name'][$i] . '  (' . $_FILES['userfile']['size'][$i] . ' bytes) on ' . $timeStampString . "</p><p align=\"left\"><br />" . "File Contents:</strong> <br /> <br />" . $filecontents . "</p><br />";
		}
	}

	$shortReceiptString = $shortReceiptString . "\n=========================================";	
	$receiptString = $receiptString . "<hr />";
	
	// restore the former umask permissions
	umask($old);
	$receiptFileName = 'Submit-' . $_POST['studentName']  . '.txt';
	
	// the 'a' flag appends txt to the file
	$fp = fopen("$studentDir$receiptFileName", "a");
	// Write the data to the file
	fwrite($fp, $shortReceiptString . "\n");
	// Close the file
	fclose($fp);
	
	emailReceipt($labName, $shortclassname, $_POST['studentName'], $studentEmail, $receiptString);
?>
<p align="left">
	<?php 
		$HTMLReceipt = str_replace("\n", "<br />", $receiptString);
		echo $HTMLReceipt;
	?>
</p>
<!--  END PHP CODE -->

			</div>
		</div>
	</body>
</html>
