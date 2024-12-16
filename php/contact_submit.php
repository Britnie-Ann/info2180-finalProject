<?php 
session_start();

$host = "localhost";
$username = "proj2_user";
$password = "groupbest1234";
$dbname = "dolphin_crm";

$title = $_POST["title"] ?? '';
$fname = $_POST["fname"] ?? '';
$lname = $_POST["lname"] ?? '';
$email = $_POST["email"] ?? '';
$telephone = $_POST["t_phone"] ?? '';
$company = $_POST["company"] ?? '';
$type = $_POST["type"] ?? '';
$assign = intval($_POST['assign_user'] ?? '0');
$created_by_user = $_SESSION['user_id'] ?? '';

$title_fil = filter_var($title, FILTER_SANITIZE_STRING);
$fname_fil = filter_var($fname, FILTER_SANITIZE_STRING);
$lname_fil = filter_var($lname, FILTER_SANITIZE_STRING);
$email_fil = filter_var($email, FILTER_SANITIZE_STRING);
$telephone_fil = filter_var($telephone, FILTER_SANITIZE_STRING);
$company_fil = filter_var($company, FILTER_SANITIZE_STRING);
$type_fil = filter_var($type, FILTER_SANITIZE_STRING);
$assign_fil = filter_var($assign, FILTER_SANITIZE_NUMBER_INT);

$conn = new mysqli($host, $username, $password, $dbname);

$sql = "INSERT INTO Contacts (title, firstname, lastname, email, telephone, company, _type, assigned_to, created_by) VALUES (?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error in the SQL query: " . $conn->error);
}

if(!$stmt->bind_param("sssssssii",$title_fil,$fname_fil,$lname_fil,$email_fil,$telephone_fil,$company_fil,$type_fil,$assign_fil,$created_by_user)){
    die("Error binding parameters: " . $stmt->error);
}

if ($stmt->execute()) {
    echo "Contact Added Successfully!";
} else {
    echo "Error Adding User!";
}

  
