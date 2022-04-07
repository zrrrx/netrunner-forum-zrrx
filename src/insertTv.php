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

    session_start();
    
    //gets connection to db
    require('connection.php');

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


    //From line 20 to 35 is code required to post content to database
    $error = false;

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
    $category = "tv";

    if(strlen($title) <= 1 || strlen($content <= 1)){
        $error == true;
    }
    else{
        $query = "INSERT INTO threads (title, content, category, author, file_name) VALUES (:title, :content, :category, :author, :file_name)";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':category', $category);
        $statement->bindValue(':author', $user);
        $statement->bindValue(":file_name", $image_filename);

        $statement->execute();
        header('location: tv.php');
        exit();
        
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/insertstyles.css">
    <title>NetRunner - Forum</title>
</head>

<body>

    <div class="header-bar">
        <h2><a href="tv.php">
                &lt;&lt; Back</a> </h2>
    </div>
    <div class="threadposition">
        <div id="threadcontainer">
            <form method="post" enctype="multipart/form-data">
                <table>
                    <tbody>
                        <tr>
                            <th>Subject</th>
                            <td>
                                <input style="float:left;" type="text" name="title" id="title" size="25" maxlength="100"
                                    autocomplete="off">
                                <input style="margin-left:2px;" type="submit" name="post" value="Post">
                            </td>
                        </tr>
                        <tr>
                            <th>Comment</th>
                            <td>
                                <textarea name="content" id="content" rows="5" cols="35" maxlength="200"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                File
                            </th>
                            <td>
                                <input type="file" name="image" id="file" style="display: block;">
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
                <input  style="visibility:hidden;" type="text" name="name" id="name" size="25" maxlength="35" autocomplete="off" value="<?= $_SESSION['sess_user_name'] ?>">
            </form>
        </div>
    </div>
</body>

</html>