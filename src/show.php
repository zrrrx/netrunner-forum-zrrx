<?php
    require('connection.php');

    if (isset($_GET['postId'])) { // Retrieve post to be edited, if id GET parameter is in URL.
        // Sanitize the id. Like above but this time from INPUT_GET.
        $postId = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parametrized SQL query using the filtered id.
        $query = "SELECT * FROM threads WHERE postId = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $postId, PDO::PARAM_INT);
        
        // Execute the SELECT and fetch the single row returned.
        $statement->execute();
        $row = $statement->fetch();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title> <?= $row['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="../css/fullpoststyles.css">
</head>
    <body>

    <div class="header-bar">
        <h2><a href="javascript:history.back()">
                &lt;&lt; Back</a> </h2>
    </div> <?php if($postId): ?> <div class="post-container">
        <div>
            <div>
                <div class="title-container">
                    <h2><?= $row['title']?></h2>
                    <p>
                        <small>Posted: <?= $row['date'] ?></small>
                    </p>
                </div>

                <div class="content-container">

                    <div class="content">
                        <img src="../assets/notavailable.png" alt="No image available" height="250" width="250">
                        <p>
                            <?= $row['content'] ?>
                        </p>
                    </div>
                </div>
                <div class="reply-container">
                    <h3><a href="#">Reply</a></h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
    </body>
</html>