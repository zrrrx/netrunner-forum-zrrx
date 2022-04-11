<?php
    
    $error = false;

    require('connection.php');

    if(!filter_input(INPUT_GET, 'replyId', FILTER_VALIDATE_INT))
    {
        $error = true;
    }
    else
    {
        $replyId = $_GET['replyId'];
    }

    $query = "DELETE FROM replies WHERE replyId = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $replyId);
    $statement->execute();

    header("Location: ../src/adminpanel.php");
    exit();
?>