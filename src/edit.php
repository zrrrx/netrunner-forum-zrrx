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
    require('authenticate.php');
    
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
<html>
<head>
    <title>Blue Bog Blog - Editing <?=$row['title']?></title>
    <link rel="stylesheet" type="text/css" href="..\css\insertstyles.css">
</head>
<body>
    <header>
        <h1><a id="title" href="prog.php"><< Back</a></h1>
    </header>
    
    <div class="threadposition">
        <div id="threadcontainer">
            <form method="post" action="<?= "update.php?postId={$row['postId']}" ?>">
                <table>
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td><input type="text" name="name" size="25" maxlength="35" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>
                                <input style="float:left;" type="text" name="title" id="title" size="25" maxlength="100" autocomplete="off" value="<?= $row['title']?>">
                                <input style="margin-left:2px;" type="submit" name="action" value="Edit">
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
                                <input type="file" name="file" id="upload_file" style="display: block;">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Captcha
                            </th>
                            <td>
                                <input type="text" name="captcha" maxlength="5" autocomplete="off">&nbsp;<span><?= generateRandomString() ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

</form>
</body>
</html> 