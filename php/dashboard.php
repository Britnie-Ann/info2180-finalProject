<?php
session_start();
require 'conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

if ($filter == 'Sales Leads') {
    $query = "SELECT * FROM contacts WHERE type = 'Sales Lead'";
} elseif ($filter == 'Support') {
    $query = "SELECT * FROM contacts WHERE type = 'Support'";
} elseif ($filter == 'Assigned') {
    $query = "SELECT * FROM contacts WHERE assigned_user_id = {$_SESSION['user_id']}";
} else {
    $query = "SELECT * FROM contacts";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dolphin CRM</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="../images/Dolphinlogo.png" alt="Dolphin CRM Logo">
        <h1>Dolphin CRM</h1>
    </div>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard.php" class="active">Home</a></li>
                <li><a href="contacts.php">New Contact</a></li>
                <li><a href="#">Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-content">
            <h1>Dashboard</h1>
            <a href="contacts.php" class="add-contact">Add New Contact</a>
        </div>

        <!-- Filters -->
        <div class="filters">
          <strong>Filter By:</strong>
          <a href="dashboard.php?filter=All" class="<?php echo (!isset($_GET['filter']) || $_GET['filter'] == 'All') ? 'active' : ''; ?>">All</a>
          <a href="dashboard.php?filter=Sales Leads" class="<?php echo (isset($_GET['filter']) && $_GET['filter'] == 'Sales Leads') ? 'active' : ''; ?>">Sales Leads</a>
          <a href="dashboard.php?filter=Support" class="<?php echo (isset($_GET['filter']) && $_GET['filter'] == 'Support') ? 'active' : ''; ?>">Support</a>
          <a href="dashboard.php?filter=Assigned" class="<?php echo (isset($_GET['filter']) && $_GET['filter'] == 'Assigned') ? 'active' : ''; ?>">Assigned to me</a>
        </div>

        <!-- Contacts Table -->
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($contact = $result->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo $contact['title'] . " " . $contact['first_name'] . " " . $contact['last_name']; ?></strong></td>
                    <td><?php echo $contact['email']; ?></td>
                    <td><?php echo $contact['company']; ?></td>
                    <td>
                        <span class="badge <?php echo strtolower(str_replace(' ', '-', $contact['type'])); ?>">
                            <?php echo $contact['type']; ?>
                        </span>
                    </td>
                    <td>
                      <a href="contact_details.php?id=<?php echo $contact['id']; ?>">View</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
