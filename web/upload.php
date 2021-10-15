<?php

require_once __DIR__ . '/vendor/autoload.php';

if (isset($_POST["submit"]) and isset($_POST["uploadkey"])) {
	if (isset($_FILES["file"])) {
		// if there was an error uploading the file
		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		} else {
			// Get uploadKey
			$uploadKey = $_POST["uploadkey"];
			
			// DB definition
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
			
			// get groupname for giving filename
			$getdata = "SELECT Groupname FROM aspect_result WHERE Uploadkey = $uploadKey";
			$result_get = $conn->query($getdata);
			$namagrup = '';
			if ($result_get) {
				while($row = $result_get->fetch_assoc()) {
					$namagrup = $row['Groupname'];
				}
			}

			// print file details
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
			$y_pred_pairs = array();
			$i = 0;
			$content = []; // variable content to store the value of uploaded docs
			while (($line = fgetcsv($fp, 1000, ",")) != false) {
				if ($line[0] >= 7000 and $line[0] <= 7199) {
					array_push($y_pred_food, $line[1]);
					array_push($y_pred_ambience, $line[2]);
					array_push($y_pred_service, $line[3]);
					array_push($y_pred_price, $line[4]);
					
					$pair_food = "FOOD" . $line[1];
					$pair_ambience = "AMBIENCE" . $line[2];
					$pair_service = "SERVICE" . $line[3];
					$pair_price = "PRICE" . $line[4];
					array_push($y_pred_pairs, array($pair_food, $pair_ambience, $pair_service, $pair_price));
				}
				$gabungan[$i] = $line[0] . "," . $line[1] . "," . $line[2] . "," . $line[3] . "," . $line[4];
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
			$y_gold_pairs = array();
			$i = 0;
			while (($line = fgetcsv($fp, 1000, ",")) != false) {
				$y_gold_food[$i] = $line[1];
				$y_gold_ambience[$i] = $line[2];
				$y_gold_service[$i] = $line[3];
				$y_gold_price[$i] = $line[4];

				$pair_food = "FOOD" . $line[1];
				$pair_ambience = "AMBIENCE" . $line[2];
				$pair_service = "SERVICE" . $line[3];
				$pair_price = "PRICE" . $line[4];
				array_push($y_gold_pairs, array($pair_food, $pair_ambience, $pair_service, $pair_price));
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
						$tp_aspect++;
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
					}
				} else {
					if ($y_gold_price[$i] != '-') {
						$fn_aspect++;
					} else {
						$fp_aspect++;
					}
				}
				$i++;
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

			// aspect-sentiment pairs
			$tp_aspect_sentiment = 0;
			$fp_aspect_sentiment = 0;
			$fn_aspect_sentiment = 0;
			$i = 0;
			while ($i < count($y_gold_pairs)) {
				$tp_aspect_sentiment = $tp_aspect_sentiment + count(array_intersect($y_gold_pairs[$i], $y_pred_pairs[$i]));
				$fp_aspect_sentiment = $fp_aspect_sentiment + count(array_diff($y_pred_pairs[$i], $y_gold_pairs[$i]));
				$fn_aspect_sentiment = $fn_aspect_sentiment + count(array_diff($y_gold_pairs[$i], $y_pred_pairs[$i]));
				$i++;
			}

			if (($tp_aspect_sentiment + $fp_aspect_sentiment) != 0) {
				$precision_aspect_sentiment = ($tp_aspect_sentiment/($tp_aspect_sentiment + $fp_aspect_sentiment))*100;
			} else {
				$precision_aspect_sentiment = 0;	
			}
				
			if (($tp_aspect_sentiment + $fn_aspect_sentiment) != 0) {
				$recall_aspect_sentiment = ($tp_aspect_sentiment/($tp_aspect_sentiment + $fn_aspect_sentiment))*100;
			} else {
				$recall_aspect_sentiment = 0;	
			}

			if (($recall_aspect_sentiment != 0) and ($precision_aspect_sentiment != 0)) {
				$f1_score_aspect_sentiment = 2/((1/$recall_aspect_sentiment) + (1/$precision_aspect_sentiment));
			} else {
				$f1_score_aspect_sentiment = 0;
			}

			// update info di basis data
			$sql = "UPDATE aspect_result SET `Precision` = $precision_aspect, `Recall` = $recall_aspect, `F1Score` = $f1_score_aspect WHERE Uploadkey='$uploadKey'";

			if ($conn->query($sql) != TRUE) {
				die("Error updating record: " . $conn->error);
			}

			echo "<strong>Precision Aspect Only</strong>: " . $precision_aspect . "<br>";
			echo "<strong>Recall Aspect Only</strong>: " . $recall_aspect . "<br>";
			echo "<strong>F1-score Aspect Only</strong>: " . $f1_score_aspect . "<br>";

			$sql = "UPDATE aspect_sentiment_result SET `Precision` = $precision_aspect_sentiment, `Recall` = $recall_aspect_sentiment, `F1Score` = $f1_score_aspect_sentiment WHERE Uploadkey='$uploadKey'";

			if ($conn->query($sql) != TRUE) {
				die("Error updating record: " . $conn->error);
			}

			echo "<strong>Precision Aspect-Sentiment Pair</strong>: " . $precision_aspect_sentiment . "<br>";
			echo "<strong>Recall Aspect-Sentiment Pair</strong>: " . $recall_aspect_sentiment . "<br>";
			echo "<strong>F1-score Aspect-Sentiment Pair</strong>: " . $f1_score_aspect_sentiment;
			echo "<br>*jika terjadi error terkait 'mysql', coba unggah sekali lagi.";
			echo "<br><br><a href='index.php'>See Current Rankings</a><br>";
				
			// ------------- keperluan save submission -------------------
			$string_input = implode('\n', $content);
			
			// update table submission untuk simpan filename yang disubmit oleh grup
			$sekarang = date("Y-m-d H:i:s");
			$sql = "INSERT INTO submission_logs(UploadKey, GroupName, filename, mime, size, updated, data) VALUES 
					('$uploadKey', '$namagrup', '$name', '$mime', '$size', '$sekarang', '$string_input')";

			if ($conn->query($sql) != TRUE) {
				die("Error submiting record: " . $conn->error);
			}	
			$conn->close();
        }
	} 
}
?>