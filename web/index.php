<?php

$host = 's3lkt7lynu0uthj8.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$username = 'uhtbugxx9ty6dufh';
$password = 'wtqo9g6jixlfm7ov';
$dbname = 'clgr9wl4akcxw07o';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Old command from anamedsos 2020
// $sql = "select * from person_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
// $result_person = $conn->query($sql);

// $sql = "select * from gender_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
// $result_gender = $conn->query($sql);

// $sql = "select * from origin_9_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
// $result_origin_9 = $conn->query($sql);

// $sql = "select * from origin_2_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
// $result_origin_2 = $conn->query($sql);

// $sql = "select * from ethnic_group_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
// $result_ethnic_group = $conn->query($sql);

//query for anamedsos 2021
$sql = "select * from isaperson_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
$result_isaperson = $conn->query($sql);

$sql = "select * from user_location_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
$result_user_location = $conn->query($sql);

$sql = "select * from job_area_result order by `complete set f1-score` desc, `complete set accuracy` desc, `complete set precision` desc, `complete set recall` desc";
$result_job_area = $conn->query($sql);
?>

<center>
<h2>Tugas Proyek Text Analytics Analitika Media Sosial</h2>
<h3>Demographics Prediction on Twitter Data</h3>
<p>Submission dibuka sampai Kamis, 1 Juli 2021, pukul 22.00 WIB</p>
<hr>
<h4>Person Prediction</h4>
<table width="1000">
	<tr>
		<td><strong>Ranking</strong></td>
		<td><strong>Group Name</strong></td>
		<td><strong>Complete Set Accuracy (%)</strong></td>
	    <td><strong>Complete Set Precision (%)</strong></td>
	    <td><strong>Complete Set Recall (%)</strong></td>
	    <td><strong>Complete Set F1-Score (%)</strong></td>
	</tr>
<?php
	// output data of each row
	$i = 1;
	while($row = $result_isaperson->fetch_assoc()) {
		echo "<tr bgcolor=\"pink\">";
		echo "<td>".$i."</td>";
		echo "<td>".$row["Groupname"]."</td>";
		echo "<td>".($row["Complete Set Accuracy"])."</td>";
    	echo "<td>".($row["Complete Set Precision"])."</td>";
    	echo "<td>".($row["Complete Set Recall"])."</td>";
    	echo "<td>".($row["Complete Set F1-Score"])."</td>";
		echo "</tr>";
		$i++;
	}
?>
</table>

<br>
Submit your test result here: <br>
(*upload file berekstensi csv dengan delimiter koma berisi id dan hasil prediksi <strong>tanpa header</strong>)
<table width="1000">
	<form action="upload_person.php" method="post" enctype="multipart/form-data">
		<tr>
			<td width="20%">Select file</td>
			<td width="80%"><input type="file" name="file" id="file" disabled></td>
		</tr>
		<tr>
			<td width="20%">Upload key</td>
			<td><input type="text" name="uploadkey" disabled></td>
		</tr>
		<tr>
			<td>Submit</td>
			<td><input type="submit" name="submit" disabled></td>
		</tr>
	</form>
</table>
<hr>

<h4>User Location Prediction</h4>
<table width="1000">
	<tr>
		<td><strong>Ranking</strong></td>
		<td><strong>Group Name</strong></td>
		<td><strong>Complete Set Accuracy (%)</strong></td>
	    <td><strong>Complete Set Precision (%)</strong></td>
	    <td><strong>Complete Set Recall (%)</strong></td>
	    <td><strong>Complete Set F1-Score (%)</strong></td>
	</tr>
<?php
	// output data of each row
	$i = 1;
	while($row = $result_user_location->fetch_assoc()) {
		echo "<tr bgcolor=\"yellow\">";
		echo "<td>".$i."</td>";
		echo "<td>".$row["Groupname"]."</td>";
		echo "<td>".($row["Complete Set Accuracy"])."</td>";
    	echo "<td>".($row["Complete Set Precision"])."</td>";
    	echo "<td>".($row["Complete Set Recall"])."</td>";
    	echo "<td>".($row["Complete Set F1-Score"])."</td>";
		echo "</tr>";
		$i++;
	}
?>
</table>

<br>
Submit your test result here: <br>
(*upload file berekstensi csv dengan delimiter koma berisi id dan hasil prediksi <strong>tanpa header</strong>)
<table width="1000">
	<form action="upload.php?type=user_location" method="post" enctype="multipart/form-data">
		<tr>
			<td width="20%">Select file</td>
			<td width="80%"><input type="file" name="file" id="file" disabled></td>
		</tr>
		<tr>
			<td width="20%">Upload key</td>
			<td><input type="text" name="uploadkey" disabled></td>
		</tr>
		<tr>
			<td>Submit</td>
			<td><input type="submit" name="submit" disabled></td>
		</tr>
	</form>
</table>
<hr>

<h4>Job Area Prediction</h4>
<table width="1000">
	<tr>
		<td><strong>Ranking</strong></td>
		<td><strong>Group Name</strong></td>
		<td><strong>Complete Set Accuracy (%)</strong></td>
	    <td><strong>Complete Set Precision (%)</strong></td>
	    <td><strong>Complete Set Recall (%)</strong></td>
	    <td><strong>Complete Set F1-Score (%)</strong></td>
	</tr>
<?php
	// output data of each row
	$i = 1;
	while($row = $result_job_area->fetch_assoc()) {
		echo "<tr bgcolor=\"lightgreen\">";
		echo "<td>".$i."</td>";
		echo "<td>".$row["Groupname"]."</td>";
		echo "<td>".($row["Complete Set Accuracy"])."</td>";
    	echo "<td>".($row["Complete Set Precision"])."</td>";
    	echo "<td>".($row["Complete Set Recall"])."</td>";
    	echo "<td>".($row["Complete Set F1-Score"])."</td>";
		echo "</tr>";
		$i++;
	}
?>
</table>

<br>
Submit your test result here: <br>
(*upload file berekstensi csv dengan delimiter koma berisi id dan hasil prediksi <strong>tanpa header</strong>)
<table width="1000">
	<form action="upload.php?type=job_area" method="post" enctype="multipart/form-data">
		<tr>
			<td width="20%">Select file</td>
			<td width="80%"><input type="file" name="file" id="file" disabled></td>
		</tr>
		<tr>
			<td width="20%">Upload key</td>
			<td><input type="text" name="uploadkey" disabled></td>
		</tr>
		<tr>
			<td>Submit</td>
			<td><input type="submit" name="submit" disabled></td>
		</tr>
	</form>
</table>
<hr>
</center>
<?php

$conn->close();

?>