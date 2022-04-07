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

    $query = "SELECT * FROM threads WHERE postId = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $postId, PDO::PARAM_INT);
    $statement->execute();
    $row = $statement->fetch();

    //This long block handles the mutliple submits from edit for the delete and edit button.
    if(strlen($title) <= 1 || strlen($content) <= 1)
    {
        $error = true;
    }
    elseif ($_POST['action'] == 'Update')
    {

        $uploaderr = " ";

        function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
            $current_folder = dirname(__FILE__);
            $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
            return join(DIRECTORY_SEPARATOR, $path_segments);
        }
        
        function file_is_valid($temporary_path, $new_path) {
            $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png', 'application/pdf'];
            $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png', 'pdf'];
    
            $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
            $actual_mime_type        = $_FILES['image']['type'];
    
            $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
            $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
    
            return $file_extension_is_valid && $mime_type_is_valid;
        }
    
        $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
     
        if ($image_upload_detected) {
            $image_filename       = $_FILES['image']['name'];
            $temporary_image_path = $_FILES['image']['tmp_name'];
            $new_image_path       = file_upload_path($image_filename);
    
            if(file_is_valid($temporary_image_path, $new_image_path)){
                move_uploaded_file($temporary_image_path, $new_image_path);
            } else {
                $uploaderr = "Error!";
            }
        }

        $query = "UPDATE threads SET title = :title, content = :content, file_name = :file_name WHERE postId = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':id', $postId, PDO::PARAM_INT);
        $statement->bindValue(':file_name', $image_filename);
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
    elseif ($_POST['action'] == 'Remove Image')
    {
        $query = "UPDATE `threads` SET `file_name` = '' WHERE `threads`.`postId` = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $postId);
        $statement->execute();

        $image = $row['file_name'];

        unlink('../src/uploads/'.DIRECTORY_SEPARATOR.$image);

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