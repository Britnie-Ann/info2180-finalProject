<?php
session_start();
require 'conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'You must be logged in to add a note.']);
    exit();
}

$user_id = $_SESSION['user_id'];

$contact_id = isset($_POST['contact_id']) ? intval($_POST['contact_id']) : 0;
$comment = trim($_POST['comment']);

if ($contact_id <= 0) {
    echo json_encode(['error' => 'Invalid contact ID.']);
    exit();
}

if (empty($comment)) {
    echo json_encode(['error' => 'Comment cannot be empty.']);
    exit();
}

$contact_check = $conn->prepare("SELECT id FROM contacts WHERE id = ?");
$contact_check->bind_param("i", $contact_id);
$contact_check->execute();
$contact_result = $contact_check->get_result();

if ($contact_result->num_rows === 0) {
    echo json_encode(['error' => 'Contact does not exist.']);
    exit();
}

$query = $conn->prepare("INSERT INTO notes (contact_id, created_by, comment) VALUES (?, ?, ?)");
$query->bind_param("iis", $contact_id, $user_id, $comment);

if ($query->execute()) {
    $notes_query = $conn->prepare("
        SELECT n.comment, n.created_at, u.first_name, u.last_name 
        FROM notes n 
        JOIN users u ON n.created_by = u.id 
        WHERE n.contact_id = ?
        ORDER BY n.created_at DESC
    ");
    $notes_query->bind_param("i", $contact_id);
    $notes_query->execute();
    $result = $notes_query->get_result();

    $notes_html = '';
    while ($note = $result->fetch_assoc()) {
        $notes_html .= "
            <div class='note'>
                <p><strong>" . htmlspecialchars($note['first_name'] . " " . $note['last_name']) . "</strong></p>
                <p>" . nl2br(htmlspecialchars($note['comment'])) . "</p>
                <small>" . date("F j, Y g:i a", strtotime($note['created_at'])) . "</small>
            </div>";
    }

    echo json_encode(['success' => true, 'html' => $notes_html]);
} else {
    echo json_encode(['error' => 'Failed to save note.']);
}
?>
