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


    require('connection.php');
    


    //This validates the posts id to make sure its a valid int, sends you home if it fails.
    if(!filter_input(INPUT_GET, 'postId', FILTER_VALIDATE_INT))
    {
        header('location: ../index.php');
        exit;
    }
    else
    {
        $postId = $_GET['postId'];
    }

    $query = "SELECT * FROM threads WHERE postId = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $postId, PDO::PARAM_INT);
    $statement->execute();
    $row = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>NetRunner - Editing <?=$row['title']?></title>
    <link rel="stylesheet" type="text/css" href="../css/insertstyles.css">
</head>

<body>
    <header>
        <h1><a href="javascript:history.back()">&lt;&lt; Back</a></h1>
    </header>

    <div class="threadposition">
        <div id="threadcontainer">
            <form method="post" enctype="multipart/form-data" action="<?= "update.php?postId={$row['postId']}" ?>" onsubmit="return confirm('Are you sure you want to make these changes?');">
                <table>
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td><input type="text" name="name" size="25" maxlength="35" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>
                                <input style="float:left;" type="text" name="title" id="title" size="25" maxlength="100"
                                    autocomplete="off" value="<?= $row['title']?>">
                                <input style="margin-left:2px;" type="submit" name="action" value="Update">
                                <input style="margin-left:2px;" type="submit" name="action" value="Delete">
                            </td>
                        </tr>
                        <tr>
                            <th>Comment</th>
                            <td>
                                <textarea name="content" id="content" rows="5" cols="35"><?=$row['content']?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                File
                            </th>
                            <td>
                                <input type="file" name="image" id="file" style="display: block;">
                                <?php if(!empty($row['file_name'])): ?>
                                    <input type="submit" name="action" value="Remove Image" style="display: block;">
                                <?php endif ?>
                                
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Captcha
                            </th>
                            <td>
                                <input type="text" name="captcha" maxlength="5"
                                    autocomplete="off">&nbsp;<span><?= generateRandomString() ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</body>

</html>