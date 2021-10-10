<?php

require_once __DIR__ . '/vendor/autoload.php';

if (isset($_POST["submit"]) and isset($_POST["uploadkey"])) {
	if (isset($_FILES["file"])) {
		//if there was an error uploading the file
		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		} else {
			// get uploadKey
			$uploadKey = $_POST["uploadkey"];
			
			// db definition
			$host = 'kfgk8u2ogtoylkq9.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
			$username = 'n0ww3uyqs0rp7f3z';
			$password = 'khb8zr794pyztrw2';
			$dbname = 'xepi4sk6to5jhqc4';

			// Create connection
			$conn = new mysqli($host, $username, $password, $dbname);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			//get groupname for giving filename
			$getdata = "SELECT GroupName FROM aspect_result WHERE UploadKey = $uploadKey";
			$result_get = $conn->query($getdata);
			$namagrup = '';
			if ($result_get) {
				while($row = $result_get->fetch_assoc()) {
					$namagrup = $row['GroupName'];
				}
			}

			// print file details
			$file = $namagrup."-".$_FILES['file']['name'];
			$file_loc = $_FILES['file']['tmp_name'];
			$folder="uploads/";
			$name = $_FILES['file']['name'];
			$mime = $_FILES['file']['type'];
			$data = $_FILES['file']['tmp_name'];
			$size = $_FILES['file']['size'];
			
			echo "<h2>.:Informasi:.</h2>";
			echo "Uploaded File: " . $name . "<br />";
			echo "Type: " . $mime . "<br />";
			echo "Size: " . ($size / 1024) . " KB<br />";
			
			$fp = fopen($data, 'r');
			$y_pred_food = array();
			$y_pred_ambience = array();
			$y_pred_service = array();
			$y_pred_price = array();

			$i = 0;
			
			$content = []; // variable content to store the value of uploaded docs
			while (($read_line = fgetcsv($fp, 1000, ",")) != false) {
				if ($i >= 7000 and $i <= 7199) {
					array_push($y_pred_food, $read_line[1]);
					array_push($y_pred_ambience, $read_line[2]);
					array_push($y_pred_service, $read_line[3]);
					array_push($y_pred_price, $read_line[4]);
				}
				$gabungan[$i] = $read_line[0] . "," . $read_line[1] . "," . $read_line[2] . "," . $read_line[3] . "," . $read_line[4];
				array_push($content, $gabungan[$i]);
				$i++;
			}
			fclose($fp);
			
			// load gold standard data
			$file_gold = 'temp_x2z/df_gold.csv';
			$fp = fopen($file_gold, 'r');
			$y_gold_food = array();
			$y_gold_ambience = array();
			$y_gold_service = array();
			$y_gold_price = array();
			$i = 0;
			while (($line = fgetcsv($fp, 1000, ",")) != false) {
				$y_gold_food[$i] = $line[1];
				$y_gold_ambience[$i] = $line[2];
				$y_gold_service[$i] = $line[3];
				$y_gold_price[$i] = $line[4];

				$i++;
			}
			fclose($fp);

			// aspect only
			$tp_aspect = 0;
			$tn_aspect = 0;
			$fp_aspect = 0;
			$fn_aspect = 0;
			$i = 0;
			while (($i < count($y_gold_food))) {
				// check food
				if (($y_gold_food[$i] == $y_pred_food[$i])) {
					if ($y_gold_food[$i] != '-') {
						$tp_aspect++;
					} else {
						$tn_aspect++;
					}
				} else {
					if ($y_gold_food[$i] != '-') {
						$fn_aspect++;
					} else {
						$fp_aspect++;
					}
				}

				// check ambience
				if (($y_gold_ambience[$i] == $y_pred_ambience[$i])) {
					if ($y_gold_ambience[$i] != '-') {
						$tp_aspect++;
					} else {
						$tn_aspect++;
					}
				} else {
					if ($y_gold_ambience[$i] != '-') {
						$fn_aspect++;
					} else {
						$fp_aspect++;
					}
				}

				// check service
				if (($y_gold_service[$i] == $y_pred_service[$i])) {
					if ($y_gold_service[$i] != '-') {
						$tp++;
					} else {
						$tn++;
					}
				} else {
					if ($y_gold_service[$i] != '-') {
						$fn_aspect++;
					} else {
						$fp_aspect++;
					}
				}

				// check price
				if (($y_gold_price[$i] == $y_pred_price[$i])) {
					if ($y_gold_price[$i] != '-') {
						$tp_aspect++;
					} else {
						$tn_aspect++;
					}
				} else {
					if ($y_gold_price[$i] != '-') {
						$fn_aspect++;
					} else {
						$fp_aspect++;
					}
				}
			}
			
			if (($tp_aspect + $fp_aspect) != 0) {
				$precision_aspect = ($tp_aspect/($tp_aspect + $fp_aspect))*100;
			} else {
				$precision_aspect = 0;	
			}
				
			if (($tp_aspect + $fn_aspect) != 0) {
				$recall_aspect = ($tp_aspect/($tp_aspect + $fn_aspect))*100;
			} else {
				$recall_aspect = 0;	
			}

			if (($recall_aspect != 0) and ($precision_aspect != 0)) {
				$f1_score_aspect = 2/((1/$recall_aspect) + (1/$precision_aspect));
			} else {
				$f1_score_aspect = 0;
			}

			// update info di basis data
			$table = 'aspect_result';
			$sql = "UPDATE $table SET `precision` = $precision_aspect, `recall` = $recall_aspect, `f1score` = $f1_score_aspect WHERE Uploadkey='$uploadKey'";

			if ($conn->query($sql) == TRUE) {
				//echo "Record updated successfully";
			} else {
				die("Error updating record: " . $conn->error);
			}

			echo "<strong>Precision</strong>: " . $precision_aspect . "<br>";
			echo "<strong>Recall</strong>: " . $recall_aspect . "<br>";
			echo "<strong>F1-score</strong>: " . $f1_score_aspect;

			echo "<br>*jika terjadi error terkait 'mysql', coba unggah sekali lagi.";
			echo "<br><br><a href='index.php'>See Current Rankings</a><br>";
				
			// ------------- keperluan save submission -------------------
			// make file name in lower case -- untuk keperluan save hasil submission di folder
			$new_file_name = strtolower($file);
			$final_file = str_replace(' ','-',$new_file_name);
			$string_input = implode('\n', $content);
			
			// jika berhasil di pindah ke folder uploads
			if(move_uploaded_file($file_loc, $folder.$final_file))
			{
			
				// update table submission untuk simpan filename yang disubmit oleh grup
				$sekarang = date("Y-m-d H:i:s");
				$sql = "INSERT INTO aspect_submission_logs(UploadKey, GroupName, filename, mime, size, updated, data) VALUES 
						('$uploadKey', '$namagrup', '$name', '$mime', '$size', '$sekarang', '$string_input')";

				if ($conn->query($sql) == TRUE) {
					// echo "Submission saved successfully";
				} else {
					die("Error submiting record: " . $conn->error);
				}

			}	
			$conn->close();
        }
	} 
}
?>