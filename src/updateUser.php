<?php

    require('connection.php');

    $error = false;

    //Validation for form inputs from edit.php.
    $user  = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
    
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

    //This long block handles the mutliple submits from edit for the delete and edit button.
    if(strlen($user) <= 1 || strlen($email) <= 1)
    {
        $error = true;
    }
    elseif ($_POST['action'] == 'Edit')
    {
        $query = "UPDATE user_accounts SET user_name = :user_name, user_email = :user_email WHERE userId = :userId";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_name', $user);
        $statement->bindValue(':user_email', $email);
        $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
        $statement->execute();
        header('Location: ../index.php');
        exit();
    }
    elseif ($_POST['action'] == 'Delete') 
    {
        $query = "DELETE FROM user_accounts WHERE userId = :userId";
        $statement = $db->prepare($query);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        header('Location: ../index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>NetRunner Forum - Error</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <header>
        <h1><a id="title" href="../index.php">NetRunner - Home</a></h1>
    </header>
    <?php if($error == true): ?>
        <p>Error: No user selected...</p>
    <?php endif ?>
</body>
</html> 