<?php
	$debug = false;
	$debugString = '';
	function mkdir_r($dirName, $rights=0777){
		if ($debug) {
			$debugString = $debugString . "mkdir_r called on " . $dirName . "<br />";
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
	
	include "myMail.php";

 	// variable inits 
	$servername = "ftp.teachopensource.org";
	$ftpuser = "submit@teachopensource.org";
	$ftppass = "ph0ebe1s";
	
	$conn_id = ftp_connect($servername) or die("No server connection.");
	
	
 	$labName = $_POST['labName']; 
 	$shortclassname = $_POST['course'];
	$rootDir = '/data/' . $shortclassname;
	$logFileName = 'submitLog.txt';
	// bluej submitter appends the labname by default
	//$uploadDir = $rootDir . $labName;
	$uploadDir = $rootDir;
	$studentDir = $uploadDir . '/' . $_POST['studentName'] . ' - ' . $_POST['studentEmail'] . '/';
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
	$numFiles = sizeof($_FILES);
	if ($debug) {
		echo $numFiles . '<br />';
		print_r($_POST);
		echo '<br />';
		print_r($_FILES);
	}

	// REMEMBER:  single quotes don't expand variables or escaped characters, double-quotes do
	// get the receipt string ready
	$shortReceiptString = "====================================================\n";
	$shortReceiptString = $shortReceiptString . "Lab Submission Report\nLab:  " . $labName . "\nSubmitted By:  ". $_POST['studentName'] . "(" . $_POST['studentEmail'] . ")\nFiles:  \n";
	$receiptString = "<html><body><hr />";
	$receiptString = $receiptString . "<strong>Lab Submission Report</strong><br /><strong>Lab:</strong>  " . $labName . "<br /><strong>Submitted by:</strong>  " . $_POST['studentName'] . " (" . $_POST['studentEmail'] . ")<br /><br /><strong>File listings:</strong><br />";

	// upload each file in turn
	if (ftp_login($conn_id, $ftpuser, $ftppass))
	{
		
	for ($i = 1; $i <= $numFiles; $i++) {
		$arrIndex = 'file' . $i;
		$fileName = $_FILES[$arrIndex]['name'];
		$dest = $studentDir . $fileName;
		$file = $_FILES[$arrIndex]['tmp_name'];
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

		if(ftp_put($conn_id, $dest, $file, FTP_BINARY)) {
			$shortReceiptString = $shortReceiptString. $_FILES[$arrIndex]['name'] .  '  (' . $_FILES[$arrIndex]['size'] . ' bytes) on ' . $timeStampString;
			$receiptString = $receiptString . "<br /><hr /><p align='center'><strong>" . $_FILES[$arrIndex]['name'] . '  (' . $_FILES[$arrIndex]['size'] . ' bytes) on ' . $timeStampString . "</p><p align=\"left\"><br />" . "File Contents:</strong> <br /> <br />" . $filecontents . "</p><br />";
		}
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