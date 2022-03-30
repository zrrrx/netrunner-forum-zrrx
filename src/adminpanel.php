<?php
    require('connection.php');

    $showQuery = "SELECT * FROM user_accounts";

    $showStatement = $db->prepare($showQuery);

    $showStatement->execute();

    $i = 0;
    
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title> Admin Panel</title>
    <link rel="stylesheet" href="../css/adminstyles.css" type="text/css">    
</head>
<html>
<body>
    <?php if($_SESSION['admin']): ?>
        <div class="header-bar">
            <h2><a style="float:right;" href="adminregister.php"> Add User</a></h2>
            <h2><a href="../index.php">&lt;&lt; Back</a></h2>
        </div>

        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Edit</th>
            </tr>
            <?php while($row = $showStatement->fetch()): ?>
                <tr>
                    <td><?= $row['user_name'] ?></td>
                    <td><?= $row['user_email'] ?></td>
                    <td><a href="<?="editUser.php?userId={$row['userId']}"?>">Edit</a></td>
                </tr>

                <? $i++ ?>
            <?php endwhile ?>
        </table>
    <?php else: ?>
        <?php header("Location: ../index.php")?>
    <?php endif ?>
</body>
</html>