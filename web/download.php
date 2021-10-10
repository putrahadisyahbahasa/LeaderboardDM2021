<?php

$id = $_GET['id'];
$waktu = $_GET['updated'];

$host = 'kfgk8u2ogtoylkq9.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$username = 'n0ww3uyqs0rp7f3z';
$password = 'khb8zr794pyztrw2';
$dbname = 'xepi4sk6to5jhqc4';

$connection =  mysqli_connect($host, $username, $password, $dbname) or die('Database Connection Failed');
mysqli_set_charset($connection,'utf-8');

$query = "SELECT * FROM submission_logs WHERE GroupName = '$id' AND updated='$waktu'";
$result = mysqli_query($connection,$query) 
	   or die('Error, query failed');
list($UploadKey, $GroupName, $filename, $mime, $size, $updated, $data) = mysqli_fetch_array($result);

$regex = '/\d+\,.*/';
preg_match_all($regex, $data, $matches);
foreach($matches[0] as $idx=>$piece) {
	echo $piece.'<br>';
}

mysqli_close($connection);

?>