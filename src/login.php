<?php
    require('connection.php');

    session_start();
    $msg = "";

    if(isset($_POST['submit'])){
        $username = trim($_POST['uname']);
        $password = trim($_POST['password']);
        if($username != "" && $password != "") {
            try {
                $query = "SELECT * FROM `user_accounts` WHERE user_name = :user_name AND password = :password";
                $stmt = $db->prepare($query);
                $stmt->bindParam('user_name', $username, PDO::PARAM_STR);
                $stmt->bindValue('password', $password, PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->rowCount();
                $row   = $stmt->fetch(PDO::FETCH_ASSOC);

                if($count == 1 && !empty($row)) {

                    $_SESSION['sess_user_id']   = $row['userId'];
                    $_SESSION['sess_user_name'] = $row['user_name'];
                    $_SESSION['sess_name'] = $row['name'];

                    header("Location: ../index.php");
                
                } else {
                    $msg = "Invalid username and password!";
                }
            } catch (PDOException $e) {
                echo "Error : ".$e->getMessage();
            }
        } else {
            $msg = "Both fields are required!";
        }
    }

    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/loginstyles.css">
    <title>NetRunner - Forum Login</title>
</head>

<body>
    <form action="login.php" method="post">

        <header>
            <h1><a href="javascript:history.back()">&lt;&lt; Back</a></h1>
        </header>

        <h2>LOGIN</h2>

        <?php if (isset($_GET['error'])) { ?>

        <p class="error"><?php echo $_GET['error']; ?></p>

        <?php } ?>

        <label>User Name</label>

        <input type="text" name="uname" placeholder="User Name"><br>

        <label>Password</label>

        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit" name="submit">Login</button>

    </form>
</body>

</html>