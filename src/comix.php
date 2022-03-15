<?php
    function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="..\css\boardstyles.css">
    <title>NetRunner - Forum</title>
</head>
<body>


    <div class="header-bar">
        <h2><a href="../index.php">NetRunner - Home</a></h2>
    </div>
    
    <div class="banner">
        <img src="..\assets\comixbanner.jpeg">
    </div>

    <div id="threadcontainer">
        <form>
            <table>
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td><input type="text" name="name" size="25" maxlength="35" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td>
                            <input style="float:left;" type="text" name="subject" size="25" maxlength="100" autocomplete="off">
                            <input style="margin-left:2px;" type="submit" name="post" value="Post">
                        </td>
                    </tr>
                    <tr>
                        <th>Comment</th>
                        <td>
                            <textarea name="body" id="body" rows="5" cols="35"></textarea>
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
</body>
</html>