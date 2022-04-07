<?php

    session_start();

    //obsolete captcha function - will remove
    function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //gets connection to db
    require('connection.php');

    //Code to show all current threads
    $showQuery = "SELECT * FROM threads WHERE category = 'comix'";

    $showStatement = $db->prepare($showQuery);

    $showStatement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/boardstyles.css">
    
    <title>NetRunner - Forum</title>
</head>
<body>


    <div class="header-bar">
        <h2><a href="../index.php">NetRunner - Home</a></h2>
    </div>
    
    <div class="banner">
        <img src="../assets/comixbanner.jpeg" alt="comics board banner">
    </div>

    <?php if(isset($_SESSION['sess_user_name'])) : ?>
        <div class="banner">
            <h3><a href="comixInsert.php" style="text-decoration:none;">[ Create Thread ]</a></h3>
        </div>
    <?php else: ?>
        <div class="banner">
            <h3><a href="login.php">Login</a> or <a href="register.pphp">Sign-Up</a> to create a new thread!</h3>
        </div>
    <?php endif ?>

    <div id="catalog">
        <?php if($showStatement->rowCount() === 0): ?>
            <p>There are currently no threads on this board!</p>
        <?php else: ?>
            
            <section class="container">
                <?php while($row = $showStatement->fetch()): ?>
                    <div class="card">
                        <div class="threadimage-container">
                            <?php if(empty($row['file_name'])): ?>
                                <img src="../assets/notavailable.png" alt="uploaded image">             
                            <?php else: ?>
                                <img src="uploads/<?= $row['file_name']?>" alt="uploaded image">
                            <?php endif ?>
                        </div>
                        <div class="content">
                            <h4>Posted by: <?= $row['author']?></h4>
                            <h2 style="color: #787cb5;"><?= $row['title'] ?></h2>
                            <a href="<?="edit.php?postId={$row['postId']}"?>">Edit</a>
                            <p>
                                <?php if (strlen($row['content']) < 100): ?>
                                    <?=$row['content']?>
                                <?php else: ?>
                                    <p><?=substr($row['content'], 0, 100)?>...</p>
                                    
                                <?php endif ?>
                            </p>
                            <br>
                            <a href="<?="show.php?postId={$row['postId']}"?>">Read Full Post...</a>
                        </div>
                    </div>
                <?php endwhile ?>
            </section>

        <?php endif ?>
    </div>
</body>
</html>