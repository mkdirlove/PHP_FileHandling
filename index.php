<?php

// Function to remove vowels from a given string
function removeVowels($str) {
  return str_replace(array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), '', $str);
}

// Function to create a new text file
function createFile($filename) {
  $file = fopen($filename, "w");
  fclose($file);
  echo "File created successfully!";
}

// Function to rename a text file
function renameFile($oldname, $newname) {
  if (file_exists($oldname)) {
    rename($oldname, $newname);
    echo "File renamed successfully!";
  } else {
    echo "File does not exist!";
  }
}

// Function to delete a text file and create a backup
function deleteFile($filename) {
  if (file_exists($filename)) {
    $backup_filename = "backup_" . $filename;
    copy($filename, $backup_filename);
    unlink($filename);
    echo "File deleted successfully! A backup file was created at $backup_filename";
  } else {
    echo "File does not exist!";
  }
}

// Main program
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["create"])) {
    createFile($_POST["filename"]);
  } elseif (isset($_POST["rename"])) {
    renameFile($_POST["oldname"], $_POST["newname"]);
  } elseif (isset($_POST["delete"])) {
    deleteFile($_POST["filename"]);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>PHP File Handling</title>
	<style>
		body {
			background-color: #4158D0;
			background-image: linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
		}
		form {
			margin: auto;
			width: 30%;
			text-align: center;
		}
		input[type=text], input[type=submit] {
			padding: 12px 20px;
			margin: 8px 0;
			box-sizing: border-box;
			border: 2px solid #ccc;
			border-radius: 4px;
			background-color: #f2f2f2;
			color: black;
			font-size: 16px;
		}
		input[type=submit] {
			background-color: #4CAF50;
			color: white;
			cursor: pointer;
		}
		input[type=submit]:hover {
			background-color: #45a049;
		}
		input[type="text"] {
			border: 1px solid #ccc;
			padding: 5px;
		}
		legend {
			font-weight: bold;
		}

	</style>
</head>
<body>
	<center>
	<h1>PHP File Handling</h1>
	</center>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<fieldset>
		<legend>CREATE NEW FILE</legend>
		Filename: <input type="text" name="filename" required placeholder="Enter filename"><br>
		<input type="submit" name="create" value="Create">
	</fieldset>
</form>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<fieldset>
		<legend>RENAME AN EXISTING FILE</legend>
		Old filename: <input type="text" name="oldname" required placeholder="Enter old filename"><br>
		New filename: <input type="text" name="newname" required placeholder="Enter new filename"><br>
		<input type="submit" name="rename" value="Rename">
	</fieldset>
</form>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<fieldset>
		<legend>DELETE AN EXISTING FILE</legend>
		Filename: <input type="text" name="filename" required placeholder="Enter filename"><br>
		<input type="submit" name="delete" value="Delete">
	</fieldset>
</form>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<fieldset>
		<legend>ALTER FILE CONTENT</legend>
		Filename: <input type="text" name="filename" required placeholder="Enter filename"><br>
		<input type="submit" name="removevowels" value="Remove Vowels">
	</fieldset>
</form>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Create File
	if (isset($_POST["create"])) {
		$filename = $_POST["filename"];
		if (file_exists($filename)) {
			echo "";
		} else {
			$file = fopen($filename, "w");
			if ($file == false) {
				echo "Error creating file.";
			} else {
				echo "File created successfully.";
				fclose($file);
			}
		}
	}
	
	// Rename File
	if (isset($_POST["rename"])) {
		$oldname = $_POST["oldname"];
		$newname = $_POST["newname"];
		if (!file_exists($oldname)) {
			echo "File does not exist.";
		} elseif (file_exists($newname)) {
			echo "File already exists.";
		} else {
			if (rename($oldname, $newname)) {
				echo "File renamed successfully.";
			} else {
				echo "Error renaming file.";
			}
		}
	}
	
	// Delete File
	if (isset($_POST["delete"])) {
		$filename = $_POST["filename"];
		if (!file_exists($filename)) {
			echo "File does not exist.";
		} else {
			$backupname = $filename . ".bak";
			if (copy($filename, $backupname)) {
				if (unlink($filename)) {
					echo "File deleted successfully.";
				} else {
					echo "Error deleting file.";
				}
			} else {
				echo "Error creating backup file.";
			}
		}
	}
	
	// Remove Vowels
	if (isset($_POST["removevowels"])) {
		$filename = $_POST["filename"];
		if (!file_exists($filename)) {
			echo "File does not exist.";
		} else {
			$file = fopen($filename, "r");
			if ($file == false) {
				echo "Error opening file.";
			} else {
				$text = fread($file, filesize($filename));
				$text = preg_replace('/[aeiou]/i', '', $text);
				fclose($file);
				$file = fopen($filename, "w");
				if ($file == false) {
					echo "Error opening file.";
				} else {
					fwrite($file, $text);
					fclose($file);
					echo "Vowels removed successfully.";
				}
			}
		}
	}
	
}
?>
