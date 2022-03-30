<?php

session_start();

require('connection.php');

if(isset($_POST['register'])){
    
    $username = !empty($_POST['user_name']) ? trim($_POST['user_name']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    $sql = "SELECT COUNT(user_name) AS num FROM user_accounts WHERE user_name = :user_name";
    $stmt = $db->prepare($sql);
    
    $stmt->bindValue(':user_name', $username);
    
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($row['num'] > 0){
        die('That username already exists!');
    }

    if($_POST['password'] == $_POST['passwordagain']){
        $passwordHash = password_hash($pass, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO user_accounts (user_name, password, user_email) VALUES (:user_name, :password, :user_email)";
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_name', $username);
        $stmt->bindValue(':password', $passwordHash);
        $stmt->bindValue(':user_email', $email);

        $result = $stmt->execute();

        if($result){
            header("Location: adminpanel.php");
        }
    }
    else{
        die('Passwords do not match');
    }

    
    
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin - Add User</title>
    <link rel="stylesheet" type="text/css" href="../css/loginstyles.css">
</head>

    
<body>
    <?php if($_SESSION['sess_user_name'] == 'admin'): ?>
        <h1 style="color: white;">Add new user account</h1>
        <form action="adminregister.php" method="post">
            <label for="email">Email</label>
            <input type="text" id="email" name="email">
            <label for="username">Username</label>
            <input type="text" id="username" name="user_name"><br>
            <label for="password">Password</label>
            <input type="password" id="password" name="password"><br>
            <label for="passwordagain">Re-Enter Password</label>
            <input type="password" id="passwordagain" name="passwordagain"><br>
            <input type="submit" name="register" value="Register"></button>
        </form>
    <?php else: ?>
        <?php header("Location: ../index.php"); ?>
    <?php endif ?>
</body>



</html>