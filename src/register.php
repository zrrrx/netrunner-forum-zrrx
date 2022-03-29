<?php

session_start();

/**
 * Include our MySQL connection.
 */
require('connection.php');

if(isset($_POST['register'])){
    
    $username = !empty($_POST['user_name']) ? trim($_POST['user_name']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    
    $sql = "SELECT COUNT(user_name) AS num FROM user_accounts WHERE user_name = :user_name";
    $stmt = $db->prepare($sql);
    
    $stmt->bindValue(':user_name', $username);
    
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($row['num'] > 0){
        die('That username already exists!');
    }
    
    $sql = "INSERT INTO user_accounts (user_name, password) VALUES (:user_name, :password)";
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_name', $username);
    $stmt->bindValue(':password', $pass);

    $result = $stmt->execute();

    if($result){
        header("Location: validregister.php");
    }
    
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>NetRunner - Register</title>
    <link rel="stylesheet" type="text/css" href="../css/loginstyles.css">
</head>

<body>
    <h1>Register</h1>
    <form action="register.php" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="user_name"><br>
        <label for="password">Password</label>
        <input type="text" id="password" name="password"><br>
        <input type="submit" name="register" value="Register"></button>
    </form>
</body>

</html>