<?php
    require('connection.php');
    require('authenticate.php');
    
    //This validates the posts id to make sure its a valid int, sends you home if it fails.
    if(!filter_input(INPUT_GET, 'userId', FILTER_VALIDATE_INT))
    {
        header('location: ../index.php');
        exit;
    }
    else
    {
        $userId = $_GET['userId'];
    }

    $query = "SELECT * FROM user_accounts WHERE userId = :userId";
    $statement = $db->prepare($query);
    $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
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
            <form method="post" action="<?= "updateUser.php?userId={$row['userId']}" ?>" onsubmit="return confirm('Are you sure you want to make these changes?');">
                <table>
                    <tbody>
                        <tr>
                            <th>Username</th>
                            <td><input type="text" name="user_name" size="25" maxlength="35" autocomplete="off"
                                value="<?= $row['user_name'] ?>"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                <input style="float:left;" type="text" name="user_email" size="25" maxlength="100"
                                    autocomplete="off" value="<?= $row['user_email']?>">

                                <input style="margin-left:2px;" type="submit" name="action" value="Edit">
                                <input style="margin-left:2px;" type="submit" name="action" value="Delete">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</body>

</html>