<?php
    require('connection.php');

    session_start();

    $error = false;

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

        $reply = filter_input(INPUT_POST, 'reply', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $author = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);

        if(strlen($reply <= 1)){
            $error = true;
        }else{
            $secondQuery = "INSERT INTO `replies` (`postId`, `reply`, `author`) VALUES (:postId, :reply, :author)";
            $stmt = $db->prepare($secondQuery);
            $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
            $stmt->bindValue(':author', $author);
            $stmt->bindValue('reply', $reply);
            $stmt->execute();

            header("Location: ../index.php");
        }

        $thirdQuery = "SELECT * FROM `replies` WHERE postId = $postId ORDER BY replyId DESC";
        $finalstmt = $db->prepare($thirdQuery);
        $finalstmt->execute();

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
                        <?php if(empty($row['file_name'])): ?>
                            <img src="../assets/notavailable.png" alt="uploaded image" width="250" height="250">
                        <?php elseif($row['file_name'] == 'n/a'): ?>
                            <img src="../assets/notavailable.png" alt="uploaded image" width="250" height="250">                 
                        <?php else: ?>
                            <img src="uploads/<?= $row['file_name']?>" alt="uploaded image" width="250" height="250">
                        <?php endif ?>
                        <p>
                            <?= $row['content'] ?>
                        </p>
                    </div>
                </div>
                <div class="reply-container">
                    <h3><a href="javascript:void(0);" class="dropdown-item" onclick="return showHide();">Reply</a></h3>
                    <form method="post" enctype="multipart/form-data">
                        <table id="reply-table" style="display:none;">
                            <tbody>
                                <tr>
                                    <th>Reply</th>
                                    <td>
                                        <textarea name="reply" id="reply" rows="5" cols="35" maxlength="200"></textarea>
                                        <input style="margin-left:2px;" type="submit" name="post" value="Post">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input  style="visibility:hidden;" type="text" name="name" id="name" size="25" maxlength="35" autocomplete="off" value="<?= $_SESSION['sess_user_name'] ?>">
                    </form>
                </div>
            </div>
        </div>
        <div class="post-replies">
            <?php if($finalstmt->rowCount() === 0): ?>
                <p>No replies yet!</p>
            <?php else: ?>
                <?php while($replyRow = $finalstmt->fetch()): ?>
                        <small>Posted by: <?= $replyRow['author']?></small>
                        <h3><?= $replyRow['reply']?></h3>
                <?php endwhile ?>
            <?php endif ?>
        </div>
    </div>
    <?php endif ?>

    <script>
        function showHide() {
         document.getElementById("reply-table").style.display = "block";
        }
    </script>
    </body>
</html>