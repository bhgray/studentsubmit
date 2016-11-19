<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!--  BEGIN PHP CODE -->

<form action="submit2.php" method="post" enctype="multipart/form-data">
<?php 
	$debug = false;
	$class = "APCS";
	$filename = 'labs' . $class . '.php';
	include "../resources/php/" . $filename;
	if ($debug) echo $filename;
	echo "<input name='filename' type='hidden' value='";
	echo $filename . "' />";
?>
  <p align="center"><strong>Lab Submission Web page.</strong></p>
  <p align="center"><strong><?php echo $class ?></strong></p>
  <p align="left"><strong>Instructions:</strong></p>
  <ol>
	<li>Select the name of the lab from the drop down list.</li>
	<li>Select your name from the drop down list.</li>
	<li>Press "Submit" -- the page will refresh to allow you to submit your files.</li>
  </ol>
  <p>Lab Name:</p>
  <select name='labName'>
	<?php
		foreach ($labFiles as $name => $files) {
			echo "<option>" . $name . "</option>";
		}
	?>
  </select>
<br />
<p>Student Name:</p>  
<select name='studentName'>
	<?php
		list($id, $info) = $studentNames;
		list($first, $last, $email) = $info;
		
		foreach($studentNames as $id => $studentArray) {
			list($first, $last, $email) = $studentArray;
			echo "<option" . " value=\"" . $id . "\">" . $id . ":  " . $last . ", " . $first . "</option>";
		}				
	?>
</select>
	<br />
	<input type="submit" value="Go to step 2" method="POST">
	  </p>
</form>
<!-- end bbinclude -->
					</div>
				</div>
			</div>
			<div id="footer">
<!-- #bbinclude "footer.incl" -->
<div class="small_text">
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/">
<img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/us/88x31.png" />
</a>
This work by 
<span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName">Brent Gray</span> is licensed under a 
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/">Creative Commons Attribution-Noncommercial-Share Alike 3.0 United States License</a>.
</div>
<div class="small_text">
	Last updated on Wednesday, August 15, 2007 at 12:52 PM.
</div>

<!--  end #bbinclude -->

			</div>
		</div>
	</body>
</html>
