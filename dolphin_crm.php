<?php
// Start the session
session_start();

// Database configuration
$host = 'localhost'; // Database host
$db = 'dolphin_crm'; // Database name
$user = 'your_username'; // Database username
$pass = 'your_password'; // Database password

// Create a new PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// User Registration
if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$first_name, $last_name, $email, $password])) {
        echo "Registration successful!";
    } else {
        echo "Registration failed.";
    }
}

// User Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dolphin_crm.php?action=dashboard");
        exit;
    } else {
        echo "Login failed.";
    }
}

// User Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: dolphin_crm.php");
    exit;
}

// Display Dashboard or Login/Register Form
if (isset($_SESSION['user_id'])) {
    // User is logged in, display dashboard
    echo "<h1>Welcome to the Dolphin CRM Dashboard!</h1>";
    echo '<a href="dolphin_crm.php?action=contacts">Manage Contacts</a><br>';
    echo '<a href="dolphin_crm.php?action=notes">Manage Notes</a><br>';
    echo '<a href="dolphin_crm.php?action=logout">Logout</a><br>';

    // Manage Contacts
    if (isset($_GET['action']) && $_GET['action'] == 'contacts') {
        echo "<h2>Contacts</h2>";

        // Create Contact
        if (isset($_POST['create_contact'])) {
            $title = $_POST['title'];
            $first_name = $_POST['contact_first_name'];
            $last_name = $_POST['contact_last_name'];
            $email = $_POST['contact_email'];
            $telephone = $_POST['telephone'];
            $company = $_POST['company'];
            $type = $_POST['type'];
            $assigned_to = $_SESSION['user_id'];

            $stmt = $pdo->prepare("INSERT INTO contacts (title, first_name, last_name, email, telephone, company, type, assigned_to, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$title, $first_name, $last_name, $email, $telephone, $company, $type, $assigned_to, $assigned_to])) {
                echo "Contact created successfully!";
            } else {
                echo "Failed to create contact.";
            }
        }

        // Display Create Contact Form
        echo '<form method="post">
                Title: <input type="text" name="title"><br>
                First Name: <input type="text" name="contact_first_name" required><br>
                Last Name: <input type="text" name="contact_last_name" required><br>
                Email: <input type="email" name="contact_email"><br>
                Telephone: <input type="text" name="telephone"><br>
                Company: <input type="text" name="company"><br>
                Type: <input type="text" name="type"><br>
                <input type="submit" name="create_contact" value="Create Contact">
              </form>';

        // List Contacts
        echo "<h3>Existing Contacts</h3>";
        $stmt = $pdo->query("SELECT * FROM contacts");
        while ($contact = $stmt->fetch()) {
            echo "<div>";
            echo "Title: " . htmlspecialchars($contact['title']) . "<br>";
            echo "First Name: " . htmlspecialchars($contact['first_name']) . "<br>";
            echo "Last Name: " . htmlspecialchars($contact['last_name']) . "<br>";
            echo "Email: " . htmlspecialchars($contact['email']) . "<br>";
            echo "Telephone: " . htmlspecialchars($contact['telephone']) . "<br>";
            echo "Company: " . htmlspecialchars($contact['company']) . "<br>";
            echo "Type: " . htmlspecialchars($contact['type']) . "<br>";
            echo "</div><hr>";
        }
    }

    // Manage Notes
    if (isset($_GET['action']) && $_GET['action'] == 'notes') {
        echo "<h2>Notes</h2>";

        // Create Note
        if (isset($_POST['create_note'])) {
            $contact_id = $_POST['contact_id'];
            $comment = $_POST['comment'];
            $created_by = $_SESSION['user_id'];

            $stmt = $pdo->prepare("INSERT INTO notes (contact_id, comment, created_by) VALUES (?, ?, ?)");
            if ($stmt->execute([$contact_id, $comment, $created_by])) {
                echo "Note created successfully!";
            } else {
                echo "Failed to create note.";
            }
        }

        // Display Create Note Form
        echo '<form method="post">
                Contact ID: <input type="text" name="contact_id" required><br>
                Comment: <textarea name="comment" required></textarea><br>
                <input type="submit" name="create_note" value="Create Note">
              </form>';

        // List Notes
        echo "<h3>Existing Notes</h3>";
        $stmt = $pdo->query("SELECT * FROM notes");
        while ($note = $stmt->fetch()) {
            echo "<div>";
            echo "Contact ID: " . htmlspecialchars($note['contact_id']) . "<br>";
            echo "Comment: " . htmlspecialchars($note['comment']) . "<br>";
            echo "</div><hr>";
        }
    }
} else {
    // User is not logged in, display login/register form
    echo '<h1>Login/Register</h1>';
    echo '<form method="post">
            Email: <input type="email" name="email" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" name="login" value="Login">
          </form>';
    echo '<form method="post">
            First Name: <input type="text" name="first_name" required><br>
            Last Name: <input type="text" name="last_name" required><br>
            Email: <input type="email" name="email" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" name="register" value="Register">
          </form>';
}
?>