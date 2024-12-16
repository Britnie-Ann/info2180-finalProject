<?php
session_start();

$host = 'localhost';
$username = '';
$password = '';
$dbname = 'dolphin_crm';

$link = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$search = $link->query("SELECT firstname,lastname,email,_role,created_at FROM Users");
$users = $search->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Created</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <?php $name = $user['firstname'].= " ";
            $name.= $user['lastname'];?>
            <tr>
                <td><?= $name ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['_role'] ?></td>
                <td><?= $user['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
