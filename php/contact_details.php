<?php  
session_start();
require 'conn.php';

if (isset($_SESSION['error'])) {
    echo "<div class='error-message'>Error</div>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo "<div class='success-message'>Success</div>";
    unset($_SESSION['success']);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

// Fetch contact details by ID
$contact_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($contact_id <= 0) {
    echo "<p>Invalid contact ID.</p>";
    exit();
}

$contact_query = $conn->prepare("SELECT c.*, u.first_name AS assigned_first, u.last_name AS assigned_last 
                                 FROM contacts c
                                 LEFT JOIN users u ON c.assigned_to = u.id
                                 WHERE c.id = ?");
$contact_query->bind_param("i", $contact_id);
$contact_query->execute();
$contact_result = $contact_query->get_result();

if ($contact_result->num_rows === 0) {
    echo "<p>Contact not found.</p>";
    exit();
}

$contact = $contact_result->fetch_assoc();

// Fetch notes for this contact
$notes_query = $conn->prepare("SELECT n.comment, n.created_at, u.first_name, u.last_name 
                               FROM notes n 
                               JOIN users u ON n.created_by = u.id 
                               WHERE n.contact_id = ?
                               ORDER BY n.created_at DESC");
$notes_query->bind_param("i", $contact_id);
$notes_query->execute();
$notes_result = $notes_query->get_result();

$contact_query = $conn->prepare("SELECT c.*, u.first_name AS assigned_first, u.last_name AS assigned_last 
                                 FROM contacts c
                                 LEFT JOIN users u ON c.assigned_to = u.id
                                 WHERE c.id = ?");
$contact_query->bind_param("i", $contact_id);
$contact_query->execute();
$contact_result = $contact_query->get_result();

if ($contact_result->num_rows === 0) {
    echo "<p>Contact not found.</p>";
    exit();
}

$contact = $contact_result->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/contact_details.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="../images/Dolphinlogo.png" alt="Dolphin CRM Logo">
        <h1>Dolphin CRM</h1>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="new_contact.php">New Contact</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="message-container">
          <?php
          if (isset($_SESSION['error'])) {
              echo "<div class='error-message'>{$_SESSION['error']}</div>";
              unset($_SESSION['error']);
          }

          if (isset($_SESSION['success'])) {
              echo "<div class='success-message'>{$_SESSION['success']}</div>";
              unset($_SESSION['success']);
          }
          ?>
      </div>
    <!-- Main Content -->
    <div class="main-content">
    <div class="header-section">
        <!-- User Name with Icon -->
        <h1>
            <i class="fas fa-user-circle"></i>
            <?php echo htmlspecialchars($contact['title'] . " " . $contact['first_name'] . " " . $contact['last_name']); ?>
        </h1>

        <!-- Created and Updated Info -->
        <div class="info-text">
            <p>Created on <?php echo date("F j, Y", strtotime($contact['created_at'])); ?></p>
            <p>Last updated on <?php echo date("F j, Y", strtotime($contact['updated_at'])); ?></p>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <form method="POST" action="change_assignment.php" style="display: inline;">
                <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
                <button type="submit" class="assign-btn">
                    <i class="fas fa-user-check"></i> Assign to me
                </button>
            </form>
            <form method="POST" action="switch_type.php" style="display: inline;">
                <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
                <input type="hidden" name="new_type" value="<?php echo ($contact['type'] === 'Sales Lead') ? 'Support' : 'Sales Lead'; ?>">
                <button type="submit" class="switch-btn <?php echo ($contact['type'] === 'Sales Lead') ? 'support' : 'sales-lead'; ?>">
                    <i class="fas fa-sync-alt"></i> 
                    Switch to <?php echo ($contact['type'] === 'Sales Lead') ? 'Support' : 'Sales Lead'; ?>
                </button>
            </form>
        </div>
    </div>


        <div class="contact-info-container">
          <div class="contact-details">
            <!-- First Column -->
            <div class="contact-column">
              <p><span>Email</span><br><?php echo htmlspecialchars($contact['email']); ?></p>
              <p><span>Company</span><br><?php echo htmlspecialchars($contact['company']); ?></p>
            </div>

            <!-- Second Column -->
            <div class="contact-column">
              <p><span>Telephone</span><br><?php echo htmlspecialchars($contact['telephone']); ?></p>
              <p><span>Assigned To</span><br><?php echo htmlspecialchars($contact['assigned_first'] . " " . $contact['assigned_last']); ?></p>
            </div>
          </div>
        </div>


        <!-- Notes Section -->
        <div class="notes">
            <h2>Notes</h2>
            <div id="notesContainer">
                <?php
                $notes_query = $conn->prepare("
                    SELECT n.comment, n.created_at, u.first_name, u.last_name 
                    FROM notes n 
                    JOIN users u ON n.created_by = u.id 
                    WHERE n.contact_id = ?
                    ORDER BY n.created_at DESC
                ");
                $notes_query->bind_param("i", $contact_id);
                $notes_query->execute();
                $notes_result = $notes_query->get_result();

                while ($note = $notes_result->fetch_assoc()) {
                    echo "<div class='note'>
                            <p><strong>" . htmlspecialchars($note['first_name'] . " " . $note['last_name']) . "</strong></p>
                            <p>" . nl2br(htmlspecialchars($note['comment'])) . "</p>
                            <small>" . date("F j, Y g:i a", strtotime($note['created_at'])) . "</small>
                        </div>";
                }
                ?>
            </div>
        </div>

        <!-- Add New Note -->
        <div class="add-note">
            <h3>Add a note about <?php echo htmlspecialchars($contact['first_name']); ?></h3>
            <form id="addNoteForm">
                <textarea name="comment" placeholder="Enter details here" rows="3" required></textarea>
                <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
                <button type="submit" class="save-btn">Save Note</button>
            </form>
        </div>


        <script src="script.js" defer></script>
    </div>
  </body>
</html>
