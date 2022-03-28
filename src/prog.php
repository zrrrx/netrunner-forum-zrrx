<?php

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
    $showQuery = "SELECT * FROM threads WHERE category = 'prog'";

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
        <img src="../assets/progbanner.jpeg" alt="banner for programming board">
    </div>

    <div class="banner">
        <h3><a href="insertProg.php" style="text-decoration:none;">[ Create Thread ]</a></h3>
    </div>

    <div id="catalog">
        <?php if($showStatement->rowCount() === 0): ?>
        <p>There are currently no threads on this board!</p>
        <?php else: ?>

        <section class="container">
            <?php while($row = $showStatement->fetch()): ?>
            <div class="card">
                <div class="threadimage-container">
                    <img src="../assets/notavailable.png" alt="no image available">
                </div>
                <div class="content">
                    <h2 style="color: aqua;"><?= $row['title'] ?></h2>
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