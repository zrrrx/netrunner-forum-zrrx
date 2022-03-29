<?php
    require('connection.php');

    $showQuery = "SELECT * FROM user_accounts";

    $showStatement = $db->prepare($showQuery);

    $showStatement->execute();
    
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title> Admin Panel</title>
    <link rel="stylesheet" href="../css/adminstyles.css" type="text/css">    
</head>
<html>
<body>

    <div class="header-bar">
        <h2><a href="../index.php">&lt;&lt; Back</a></h2>
    </div>

    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php while($row = $showStatement->fetch()): ?>
            <tr>
                <td><?= $row['user_name'] ?></td>
                <td><?= $row['user_email'] ?></td>
                <td><button onclick="window.location.href = '../index.php'">...</button></td>
                <td><button onclick="window.location.href = '../index.php'">...</button></td>
            </tr>
        <?php endwhile ?>
    </table>
</body>
</html>