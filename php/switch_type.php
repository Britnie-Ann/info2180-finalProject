<?php 
session_start();
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_id = intval($_POST['contact_id']);
    $new_type = $_POST['new_type'];

    if (!in_array($new_type, ['Sales Lead', 'Support'])) {
        echo "Invalid type provided.";
        exit();
    }

    $query = $conn->prepare("UPDATE contacts SET type = ?, updated_at = NOW() WHERE id = ?");
    $query->bind_param("si", $new_type, $contact_id);

    if ($query->execute()) {
        header("Location: contact_details.php?id=$contact_id");
        exit();
    } else {
        echo "Error: Unable to switch contact type.";
    }
}
?>
