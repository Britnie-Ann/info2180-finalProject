<?php

$host = "localhost";
$username = "";
$password = "";
$dbname = "dolphin_crm";
$options = '<option value=""></option>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'contact_submit.php';
exit(0);}

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
if ($conn->connect_error) {
    die("Failed to connect: " . $conn->connect_error);
}

$sql = "SELECT id,firstname,lastname FROM Users";
$result = $conn->query($sql);
$rows = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $row ) {
    $options .= '<option value='.$row['id'].'>'. $row['firstname']. " ". $row['lastname']. '</option>';
}

echo $options;

?>
