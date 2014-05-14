<?php
/* 
 * This software is released under the BSD 2-clause (simplified) license.
 * 
 * Copyright (c) 2014, J.Valentine (LunarCMS.com, jv@thevdm.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer. 
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those
 * of the authors and should not be interpreted as representing official policies, 
 * either expressed or implied, of the FreeBSD Project.
 */

/* Check if the stage is set, if not set it to 1 */
$stage = '1';
if(isset($_POST['stage1'])) {
	$stage = '2';
}
$sub2='n';
$sub3='n';
$sub4='n';
if (isset($_POST['stage2'])) {
	$stage = '2';
	/* Set submitted to 'y' */
	$sub2 = 'y';
	/* Set the error to '0' */
	$err = '0';
	/* Set the variables from the form data */
	$sub_host = $_POST['dbHost'];
	$sub_user = $_POST['dbUser'];
	$sub_password = $_POST['dbPass'];
	$sub_name = $_POST['dbName'];
	/* Test the connection with a simple mysqli test */
	$testDB = mysqli_connect($sub_host, $sub_user, $sub_password, $sub_name);
	if($testDB == false){
		/* If the connection failed, show an error and change the error status to '1' */
		$dbError = "<div class='notification'>There was an error connecting to the database, please check the details entered and resubmit the form.</div>";
		$err = '1';
	} else {
		/* Set the configure file variable */
		$configFile = '../includes/configure.php';
		/* Create the configure.php file saving the db information */
		$handle = fopen($configFile, 'w') or die('Cannot open file:  '.$configFile);
		$data = '<?php' . "\n" . '/* Stop the configure file being accessed directly */' . "\n" . 'if(basename(__FILE__) == basename($_SERVER[\'PHP_SELF\'])){' . "\n\t" . 'header("Location: ../");' . "\n" . '}' . "\n\n";
		$data = $data . '$bdd = new PDO("mysql:host=localhost;dbname=' . $sub_name . '", "' . $sub_user . '", "' . $sub_password . '");' . "\n\n";
		$code = $sub_host . $sub_user . $sub_name;
		$code = sha1($code);
		$data = $data . '/* Session Name */' . "\n" . '$secure = "' . $code . '";' . "\n\n" . '?>';
		fwrite($handle, $data);
		/* Now we can include the config file for use */
		include('../includes/configure.php');
		/* Create the database tables */
		$table = "contact_form";
		try {
		     $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
		     $sql ="CREATE table $table(
		     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
		     email VARCHAR( 1024 ) NOT NULL, 
		     subject VARCHAR( 1024 ) NOT NULL,
		     sent longtext NOT NULL, 
		     error longtext NOT NULL)" ;
		     $bdd->exec($sql);
		} catch(PDOException $e) {
		    echo $e->getMessage();//Remove in production code
		}
		$table = "pages";
		try {
		     $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
		     $sql ="CREATE table $table(
		     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
		     type VARCHAR( 32 ) NOT NULL, 
		     title longtext NOT NULL,
		     linkText VARCHAR( 32 ) NOT NULL, 
		     menu VARCHAR( 32 ) NOT NULL,
		     pageContent longtext NOT NULL, 
		     metaKeywords longtext NOT NULL, 
		     metaDescription longtext NOT NULL, 
		     extension VARCHAR( 1024 ) NOT NULL, 
		     extPosition VARCHAR( 32 ) NOT NULL,
		     externalURL longtext NOT NULL, 
		     destination VARCHAR( 32 ) NOT NULL, 
		     sort INT( 32 ) NOT NULL)" ;
		     $bdd->exec($sql);
		} catch(PDOException $e) {
		    echo $e->getMessage();//Remove in production code
		}
		$table = "settings";
		try {
		     $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
		     $sql ="CREATE table $table(
		     ID INT( 1 ) AUTO_INCREMENT PRIMARY KEY,
		     homepage VARCHAR( 32 ) NOT NULL, 
		     template VARCHAR( 1024 ) NOT NULL,
		     siteName VARCHAR( 1024 ) NOT NULL, 
		     siteURL VARCHAR( 1024 ) NOT NULL,
		     adminFolder VARCHAR( 1024 ) NOT NULL, 
		     timeZone VARCHAR( 1024 ) NOT NULL, 
		     users VARCHAR( 5 ) NOT NULL,
		     stats VARCHAR( 5 ) NOT NULL, 
		     seo VARCHAR( 5 ) NOT NULL)" ;
		     $bdd->exec($sql);
		} catch(PDOException $e) {
		    echo $e->getMessage();//Remove in production code
		}
		$table = "stats";
		try {
		     $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
		     $sql ="CREATE table $table(
		     ID INT( 128 ) AUTO_INCREMENT PRIMARY KEY,
		     browser VARCHAR( 1024 ) NOT NULL, 
		     version VARCHAR( 1024 ) NOT NULL,
		     os VARCHAR( 1024 ) NOT NULL, 
		     day VARCHAR( 128 ) NOT NULL,
		     month VARCHAR( 128 ) NOT NULL, 
		     year VARCHAR( 128 ) NOT NULL, 
		     hour VARCHAR( 128 ) NOT NULL, 
		     page VARCHAR( 128 ) NOT NULL, 
		     ip VARCHAR( 128 ) NOT NULL)" ;
		     $bdd->exec($sql);
		} catch(PDOException $e) {
		    echo $e->getMessage();//Remove in production code
		}
		$table = "users";
		try {
		     $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
		     $sql ="CREATE table $table(
		     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
		     access INT( 1 ) NOT NULL, 
		     name VARCHAR( 128 ) NOT NULL,
		     email VARCHAR( 128 ) NOT NULL, 
		     password VARCHAR( 128 ) NOT NULL)" ;
		     $bdd->exec($sql);
		} catch(PDOException $e) {
		    echo $e->getMessage();//Remove in production code
		}
		/* Tables created */
		/* Insert a demo page */
		$demoType = 'local';
		$demoTitle = 'LunarCMS: Now installed';
		$demoLinkText = 'Installed';
		$demoMenu = 'top';
		$demoPageContent = 'You have successfully installed Luna CMS';
		$demoMetaKeywords = '';
		$demoMetaDescription = '';
		$demoExtension = '';
		$demoExtPosition = 'above';
		$demoSort = '1';
		$insertPage = "INSERT INTO pages (type,title,linkText,menu,pageContent,metaKeywords,metaDescription,extension,extPosition,sort) VALUES(:type,:title,:linkText,:menu,:pageContent,:metaKeywords,:metaDescription,:extension,:extPosition,:sort)";
		$queryPage = $bdd->prepare($insertPage);
		$queryPage->execute(array(':type'=>$demoType,
								  ':title'=>$demoTitle,
								  ':linkText'=>$demoLinkText,
								  ':menu'=>$demoMenu,
								  ':pageContent'=>$demoPageContent,
								  ':metaKeywords'=>$demoMetaKeywords,
								  ':metaDescription'=>$demoMetaDescription,
								  ':extension'=>$demoExtension,
								  ':extPosition'=>$demoExtPosition,
								  ':sort'=>$demoSort));
		/* Now the tables have been created, direct the user to stage 3 */
		$stage = '3';
	}
}
if (isset($_POST['stage3'])) {
	include('../includes/configure.php');
	$stage = '3';
	/* Set submitted to 'y' */
	$sub3 = 'y';
	/* Set the error to '0' */
	$err = '0';
	$sub_name=$_POST['name'];
	$sub_url=$_POST['url'];
	$sub_folder=$_POST['folder'];
	$sub_stats=$_POST['stats'];
	$sub_seo='0';
	$sub_users='0';
	$sub_timeZone=$_POST['timeZone'];
	$sub_homepage='Installed';
	$sub_template='grey_site';
	/* Check that the site name has been entered */
	if (trim($sub_name) == '') {
		$name_error = '<span class="error">This field is required.</span>';
		$err = '1';
	}
	/* Check that the site URL has been entered */
	if (trim($sub_url) == '') {
		$url_error = '<span class="error">This field is required.</span>';
		$err = '1';
	}
	/* Check that the admin folder has been entered */
	if (trim($sub_folder) == '') {
		$folder_error = '<span class="error">This field is required.</span>';
		$err = '1';
	}
	/* If there are no errors submit the form */
	if ($err == '0') {
		$insertSettings = "INSERT INTO settings (homepage,template,siteName,siteURL,adminFolder,timeZone,users,stats,seo) VALUES(:homepage,:template,:siteName,:siteURL,:adminFolder,:timeZone,:users,:stats,:seo)";
		$querySettings = $bdd->prepare($insertSettings);
		$querySettings->execute(array(':homepage'=>$sub_homepage,
								  ':template'=>$sub_template,
								  ':siteName'=>$sub_name,
								  ':siteURL'=>$sub_url,
								  ':adminFolder'=>$sub_folder,
								  ':timeZone'=>$sub_timeZone,
								  ':users'=>$sub_users,
								  ':stats'=>$sub_stats,
								  ':seo'=>$sub_seo));
		/* Now the tables have been created, direct the user to stage 4 */
		$stage = '4';
	}
}
if (isset($_POST['stage4'])) {
	include('../includes/configure.php');
	$stage = '4';
	$sub4 = 'y';
	$sub_name = $_POST['name'];
	$sub_email = $_POST['email'];
	$sub_password1 = $_POST['password1'];
	$sub_password2 = $_POST['password2'];
	$sub_access = '0';
	/* PHP validation
	/* Make sure a name has been entered */
	if (trim($sub_name) == '') {
		$name_error = '<span class="error">This field is required.</span>';
		$err = '1';
	}
	/* Load the user data from the database and check the email value agains that of the form */
	$emailUsed = $bdd->query('SELECT email FROM users');
	while ($emailData = $emailUsed->fetch())
	{
		if ($sub_email == $emailData['email']) {
			$email_error = '<span class="error">The E-mail address: \'' . $sub_email . '\' is in use with another acount.</span>';
			$err = '1';
		}
	}
	if ($sub_password1 != $sub_password2) {
		$password_error = '<span class="error">The password fields do not match.</span>';
		$err = '1';
	} else {
		$password = sha1($sub_password1);
	}
	if ($err == '') {
		/* submit the form */
		$insertUser = "INSERT INTO users (access,name,email,password) VALUES(:access,:name,:email,:password)";
		$queryUser = $bdd->prepare($insertUser);
		$queryUser->execute(array(':access'=>$sub_access,
								  ':name'=>$sub_name,
								  ':email'=>$sub_email,
								  ':password'=>$password));
		$stage = '5';
	}
}
?>
<!DOCTYPE HTML>
<head>
	<title>LunarCMS - Installation</title>
	<link rel="stylesheet" type="text/css" href="includes/admin.css">
</head>
<body>
	<div id="headder">
		<div id="logo"><img src="img/logo.png" /></div>
	</div>

	<div id="contentbox">
	<?php if($stage == '1') { ?>
		<strong>Welcome to the Lunar CMS install script</strong><br>
		<br>
		This script is designed to guide you through installing Lunar CMS on your web server. Before using this install script 
		you will need to have created a single MySQL database and have the username, password, database name &amp; host name 
		(usually localhost) at hand.<br>
		<br>
		This script runs in a few simple steps:<br>
		<strong>Step 1:</strong> Introduction (this page).<br>
		<strong>Step 2:</strong> Connecting to the database, once connected the tables will be automatically created, and a 
		configuration file will be created saving the database details.<br>
		<strong>Step 3:</strong> General site settings including: Site name, URL, Time zone.<br>
		<strong>Step 4:</strong> Creating the 'Super user' account for the website owner.<br>
		<strong>Step 5:</strong> This step gives a summary of the installation, this will give information on any manual post 
		installation steps are required<br>
		<br>
		Pre-checks:<br>
		<?php
		$err = '0';
		$configFile = '../includes/configure.php';
		if (is_writable($configFile)) {
		    echo '<span style="color:green">The config file is writable</span>';
		} else {
			$err = '1';
		    echo '<span style="color: red;">The config file is not writeable</span>. To correct this please change the permissions for the file \'/includes/configure.php\' to make it writeable (CHMOD 0777). If you are usure of how to do this please contact your web host as the process varies depending on the servers software.';
		}
		echo '<br>';
		$fileFolder = '../files/';
		if (is_writable($fileFolder)) {
		    echo '<span style="color:green">The files folder is writable</span>';
		} else {
			$err = '1';
		    echo '<span style="color: red;">The file folder is not writeable</span>. To correct this please change the permissions for the folder \'/files\' to make it writeable (CHMOD 0777). If you are usure of how to do this please contact your web host as the process varies depending on the servers software.';
		}
		echo '<br><br>';
		if ($err == '0') {
		?>
			<form method='post'>
				<button name="stage1" value="stage1" type="submit" class="formbutton">Continue</button>
			</form>
		<?php } else {
			echo 'Please correct the above errors and refresh this page to continue.';
		}
		?>
	<?php } elseif($stage == '2') { ?>
		<strong>Database information</strong><br>
		<br>
		<form method='post'>
			<?php
			/* Check if the form has been submitted */

			if(isset($dbError)) {
				echo $dbError;
				echo '<br>';
			}
			?>
			<label>Database Host:</label><br>
			<input class="form" type="text" name="dbHost" value="<?php if ($sub2 == 'y') { echo $sub_host; } else { echo 'localhost'; } ?>" /><br>
			<br>
			<label>Database Name:</label><br>
			<input class="form" type="text" name="dbName" value="<?php if ($sub2 == 'y') { echo $sub_name; } ?>" /><br>
			<br>
			<label>Database Username:</label><br>
			<input class="form" type="text" name="dbUser" value="<?php if ($sub2 == 'y') { echo $sub_user; } ?>" /><br>
			<br>
			<label>Database Password:</label><br>
			<input class="form" type="password" name="dbPass" /><br>
			<br>
			<button name="stage2" value="stage2" type="submit" class="formbutton">Continue</button>
		</form>
	<?php } elseif($stage == '3') { ?>
		<strong>Site Settings</strong><br>
		<br>
		<?php
		/* Check if the form has been submitted */
		/* Generate a timezone list */
		function tz_list() {
			$zones_array = array();
			$timestamp = time();
			foreach(timezone_identifiers_list() as $key => $zone) {
				date_default_timezone_set($zone);
				$zones_array[$key]['zone'] = $zone;
			}
			return $zones_array;
		}
		?>
		<form method="post">
			<label>Website Name:</label><span class='error'>*</span> <?php echo $name_error; ?><br />
			<input type="text" id="name" name="name" class="form" value="<?php if($sub3=='y') { echo $sub_name; } ?>" /><br><br>
			<label>Website URL:</label><span class='error'>*</span> <?php echo $url_error; ?><br />
			<?php
			$guessURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$guessURL = substr($guessURL, 0, -17);
			?>
			<input type="text" id="url" name="url" class="form" value="<?php if($sub3=='y') { echo $sub_url; } else { echo $guessURL; } ?>" /><br><br>
			<label>Admin Folder:</label><span class='error'>*</span> <?php echo $folder_error; ?><br />
			<input type="text" id="folder" name="folder" class="form" value="<?php if($sub3=='y') { echo $sub_folder; } else { echo 'admin/'; } ?>" readonly /><br><br>
			<label>Time Zone</label><br>
			<select name="timeZone" class="form">
				<?php foreach(tz_list() as $t) { ?>
					<option value="<?php print $t['zone'] ?>" <?php if(($sub3=='y') && ($sub_timeZone == $t['zone'])) { echo ' selected'; } ?>>
						<?php print $t['zone'] ?>
					</option>
				<?php } ?>
			</select><br><br>
			<label>Website Stats</label><br>
			<select name="stats" class="form">
				<option value="1"<?php if(($sub3=='y') && ($sub_stats == '1')) { echo ' selected'; } elseif($settings['stats'] == '1') { echo ' selected'; } ?>>Enabled</option>
				<option value="0"<?php if(($sub3=='y') && ($sub_stats == '0')) { echo ' selected'; } elseif($settings['stats'] == '0') { echo ' selected'; } ?>>Disabled</option>
			</select><br><br>
			<button name="stage3" value="stage3" type="submit" class="formbutton">Continue</button>
		</form>
	<?php } elseif($stage == '4') { ?>
		<strong>Super user account</strong><br>
		<br>
		<form method="post">
			<label>Name:</label><span class='error'>*</span> <?php echo $name_error; ?><br />
			<input type="text" id="name" name="name" class="form" <?php if($sub4=='y') { echo "value='" . $sub_name . "' "; } ?> /><br><br>
			<label>E-mail:</label><span class='error'>*</span> <?php echo $email_error; ?><br />
			<input type="email" id="email" name="email" class="form" <?php if($sub4=='y') { echo "value='" . $sub_email . "' "; } ?> /><br><br>
			<label>Password:</label><span class='error'>*</span> <?php echo $password_error; ?><br />
			<input type="password" id="password1" name="password1" class="form" <?php if($sub4=='y') { echo "value='" . $sub_password1 . "' "; } ?> /><br><br>
			<label>Confirm Password:</label><span class='error'>*</span><br />
			<input type="password" id="password2" name="password2" class="form" <?php if($sub4=='y') { echo "value='" . $sub_password2 . "' "; } ?> /><br><br>
			<button name="stage4" value="stage4" type="submit" class="formbutton">Continue</button>
		</form>
	<?php } elseif($stage == '5') { ?>
		<strong>Summary</strong>
		configure.php - <span style="color: green">Created</span><br>
		Database tables created - <span style="color: green">Created</span><br>
		Instalation page inserted - <span style="color: green">Created</span><br>
		Site settings inserted - <span style="color: green">Created</span><br>
		Super user account inserted - <span style="color: green">Created</span><br>
		<?php 
		$configFile = '../includes/configure.php';
		chmod($configFile, 0644);
		if (is_writable($configFile)) {
		    echo '<span style="color: red">The config file is writable</span>. To correct this please change the permissions for the file \'/includes/configure.php\' to make it read only for everybody but the owner (CHMOD 0644). If you are usure of how to do this please contact your web host as the process varies depending on the servers software.<br>';
		} else {
			$err = '1';
		    echo 'config.php set to read only - <span style="color: green;">Done</span><br>';
		}
		?>
		<br>
		<a href="../" target="_blank">View your website</a> - <a href="index.php" target="_blak">Log-in to the admin panel.</a><br>
		<br>
		<a href="install.php?stage=6">Delete the install script</a>
		
	<?php } elseif($stage == '6') {
		unlink('install.php');
		echo '<meta http-equiv="refresh" content="0"; URL="index.php">';
	}
	?>
	</div>
	<div id="footer">Lunar CMS Administration Panel. Copyright &copy; Lunar CMS 2014</div>
</body>