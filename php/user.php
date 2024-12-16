<?php
// list_users.php

// Include the database connection file
require 'conn.php';


// Execute the query to get all users
$stmt = $conn->query("SELECT u.id AS user_id, CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.email, u.role, DATE_FORMAT(u.created_at, '%Y-%m-%d') AS created_date 
FROM users u");

//Results
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <title>User Details</title>
		<!--css File-->
		<link href="user.css" type="text/css" rel="stylesheet" />
	</head>
    <body>
        <div id="topSide">
            <h1 id="title">Users</h1>
            <button id="addUser">Add User</button>
        </div>

        <!--Table containing user innformation-->
        <table>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date Created</th>
            </tr>
            <?php foreach($result as $row): ?>
                <tr> 
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><?= $row['created date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>
