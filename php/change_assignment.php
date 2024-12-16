<?php  
session_start();
require 'conn.php';

if (!$conn) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: contact_details.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$contact_id = isset($_POST['contact_id']) ? intval($_POST['contact_id']) : 0;

if (!filter_var($contact_id, FILTER_VALIDATE_INT) || $contact_id <= 0) {
    $_SESSION['error'] = "Invalid contact ID.";
    header("Location: contact_details.php");
    exit();
}

$query = $conn->prepare("SELECT assigned_to, first_name, last_name FROM contacts WHERE id = ?");
$query->bind_param("i", $contact_id);

if (!$query || !$query->execute()) {
    $_SESSION['error'] = "Failed to fetch contact details.";
    header("Location: contact_details.php?id=$contact_id");
    exit();
}

$result = $query->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Contact not found.";
    header("Location: contact_details.php?id=$contact_id");
    exit();
}

$contact = $result->fetch_assoc();

if ($contact['assigned_to'] == $user_id) {
    echo "<script>
        alert('{$contact['first_name']} {$contact['last_name']} is already assigned to you.');
        window.location.href = 'contact_details.php?id=$contact_id';
    </script>";
    exit();
}

$update_query = $conn->prepare("UPDATE contacts SET assigned_to = ?, updated_at = NOW() WHERE id = ?");
$update_query->bind_param("ii", $user_id, $contact_id);

if (!$update_query || !$update_query->execute()) {
    $_SESSION['error'] = "Error: Unable to update assignment.";
    header("Location: contact_details.php?id=$contact_id");
    exit();
}

$_SESSION['success'] = "{$contact['first_name']} {$contact['last_name']} has been successfully assigned to you.";
header("Location: contact_details.php?id=$contact_id");
exit();
?>
