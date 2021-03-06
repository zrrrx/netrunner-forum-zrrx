<?php

    session_start();

    require('connection.php');

    $showQuery = "SELECT * FROM user_accounts";

    $showStatement = $db->prepare($showQuery);

    $showStatement->execute();

    $i = 0;

    $qr = "SELECT * FROM replies";
    $stmt = $db->prepare($qr);
    $stmt->execute();
    
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset="utf-8">
    <title> Admin Panel</title>
    <link rel="stylesheet" href="../css/adminstyles.css" type="text/css">    
</head>

<body>
    <?php if($_SESSION['sess_user_name'] == 'admin'): ?>
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
        <br>
        <br>
        <table>
            <tr>
                <th>Reply Id</th>
                <th>Post Id</th>
                <th>Author</th>
                <th>Reply</th>
            </tr>
            <?php while($replyRow = $stmt->fetch()): ?>
                <tr>
                    <td><?= $replyRow['replyId'] ?></td>
                    <td><?= $replyRow['postId'] ?></td>
                    <td><?= $replyRow['author'] ?></td>
                    <td><?= $replyRow['reply'] ?></td>
                    <td><a href="<?="deleteReply.php?replyId={$replyRow['replyId']}"?>">Delete</td>
                </tr>

                <? $i++ ?>
            <?php endwhile ?>
        </table>
    <?php else: ?>
        <?php header("Location: ../index.php"); ?>
    <?php endif ?>
</body>
</html>