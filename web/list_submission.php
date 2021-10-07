<?php

$host = 's3lkt7lynu0uthj8.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$username = 'uhtbugxx9ty6dufh';
$password = 'wtqo9g6jixlfm7ov';
$dbname = 'clgr9wl4akcxw07o';

// Connect to the database
$dbLink = new mysqli($host, $username, $password, $dbname);
if(mysqli_connect_errno()) {
    die("MySQL connection failed: ". mysqli_connect_error());
}
 
// Query for a list of all existing files
$type = $_GET['type'];
$table = $type . '_submission_logs';
$sql = "SELECT GroupName, UploadKey, filename, mime, size, data, updated, Accuracy, Precision_C, Recall, F1Score FROM $table ORDER BY GroupName, updated DESC";
$result = $dbLink->query($sql);
 
// Check if it was successfull
if($result) {
    // Make sure there are some files in there
    if($result->num_rows == 0) {
        echo '<p>There are no files in the database</p>';
    }
    else {
        // Print the top of a table		
        echo '<table width="100%" border="1">
                <tr>
                    <td><b>Group Name</b></td>
					<td><b>FileName</b></td>
                    <td><b>Mime</b></td>
                    <td><b>Size (bytes)</b></td>
                    <td><b>Created</b></td>
					<td><b>Accuracy<b></td>
					<td><b>Precision<b></td>
					<td><b>Recall<b></td>
                    <td><b>F1-Score</b></td>
                </tr>';
 
        // Print each file
        while($row = $result->fetch_assoc()) {
			$groupName = $row['GroupName'];
			$waktu = $row['updated'];
            echo "
                <tr>
					<td>{$row['GroupName']}</td>
                    <td>{$row['filename']}</td>
                    <td>{$row['mime']}</td>
                    <td>{$row['size']}</td>
                    <td>{$row['updated']}</td>
					<td>{$row['Accuracy']}</td>
					<td>{$row['Precision_C']}</td>
					<td>{$row['Recall']}</td>
					<td>{$row['F1Score']}</td>
					<td>
					<a href='download.php?type=$type&id=$groupName&updated=$waktu'>
					Download
					</a>
					</td>
                    
                </tr>";
        }
 
        // Close table
        echo '</table>';
    }
 
    // Free the result
    $result->free();
}
else
{
    echo 'Error! SQL query failed:';
    echo "<pre>{$dbLink->error}</pre>";
}
 
// Close the mysql connection
$dbLink->close();

?>