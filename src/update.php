<?php

    require('connection.php');

    $error = false;

    //Validation for form inputs from edit.php.
    $title  = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
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

    //This long block handles the mutliple submits from edit for the delete and edit button.
    if(strlen($title) <= 1 || strlen($content) <= 1)
    {
        $error = true;
    }
    elseif ($_POST['action'] == 'Edit')
    {
        $query = "UPDATE threads SET title = :title, content = :content WHERE postId = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':id', $postId, PDO::PARAM_INT);
        $statement->execute();
        header('Location: ../index.php');
        exit();
    }
    elseif ($_POST['action'] == 'Delete') 
    {
        $query = "DELETE FROM threads WHERE postId = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $postId);
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
        <p>Error: Please fill out the form completely.</p>
    <?php endif ?>
</body>
</html> 