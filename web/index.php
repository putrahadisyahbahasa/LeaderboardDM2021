<?php

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

// get the data
$sql = "select * from aspect_result order by `f1score` desc, `precision` desc, `recall` desc";
$result_aspect = $conn->query($sql);

$sql = "select * from aspect_sentiment_result order by `f1score` desc, `precision` desc, `recall` desc";
$result_aspect_sentiment = $conn->query($sql);
?>

<center>
<h2>Tugas Kelompok #1 Penambangan Data</h2>
<h3>Aspect-Based Restaurant Review Sentiment Analysis</h3>
<p>Submission dibuka sampai Minggu, 7 November 2021, pukul 22.00 WIB</p>
<hr>
<table width="1000">
	<tr>
		<td><strong>Ranking</strong></td>
		<td><strong>Group Name</strong></td>
	    <td><strong>Precision (%)</strong></td>
	    <td><strong>Recall (%)</strong></td>
	    <td><strong>F1-Score (%)</strong></td>
	</tr>
<?php
	// output data of each row
	$i = 1;
	while($row = $result_aspect->fetch_assoc()) {
		echo "<tr bgcolor=\"pink\">";
		echo "<td>".$i."</td>";
		echo "<td>".$row["Groupname"]."</td>";
    	echo "<td>".($row["Precision"])."</td>";
    	echo "<td>".($row["Recall"])."</td>";
    	echo "<td>".($row["F1Score"])."</td>";
		echo "</tr>";
		$i++;
	}
?>
</table>
<hr>
<table width="1000">
	<tr>
		<td><strong>Ranking</strong></td>
		<td><strong>Group Name</strong></td>
	    <td><strong>Precision (%)</strong></td>
	    <td><strong>Recall (%)</strong></td>
	    <td><strong>F1-Score (%)</strong></td>
	</tr>
<?php
	// output data of each row
	$i = 1;
	while($row = $result_aspect_sentiment->fetch_assoc()) {
		echo "<tr bgcolor=\"pink\">";
		echo "<td>".$i."</td>";
		echo "<td>".$row["Groupname"]."</td>";
    	echo "<td>".($row["Precision"])."</td>";
    	echo "<td>".($row["Recall"])."</td>";
    	echo "<td>".($row["F1Score"])."</td>";
		echo "</tr>";
		$i++;
	}
?>
</table>

<br>
Submit your test result here: <br>
(*upload file berekstensi csv dengan delimiter koma berisi id dan hasil prediksi <strong>tanpa header</strong>)
<table width="1000">
	<form action="upload.php" method="post" enctype="multipart/form-data">
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
</center>
<?php

$conn->close();

?>