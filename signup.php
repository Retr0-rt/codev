<?php
    session_start();
    $db = new SQLite3("codev.db");
    if(isset($_POST['submit'])){
        $input_first_name = $_POST['firstName'];
        $input_second_name = $_POST['secondName'];
        $input_email = $_POST['email'];
        $input_username = $_POST['username'];
        $input_password = $_POST['password'];
        $input_confirm_password = $_POST['confirm_password'];
        $role = 'Developer';
        if($input_confirm_password !== $input_password){
            $_SESSION['errors'] = "Make sure the passwords match ^-^";
            header("Location: signup.php");
            exit();
        }
        $sql = "INSERT INTO users (first_name, last_name, email, username, password_hash, role) 
        VALUES (:first, :second, :email, :username, :password, :role)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":first", $input_first_name, SQLITE3_TEXT);
        $stmt->bindValue(":second", $input_second_name, SQLITE3_TEXT);
        $stmt->bindValue(":email", $input_email, SQLITE3_TEXT);
        $stmt->bindValue(":username", $input_username, SQLITE3_TEXT);
        $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);
        $stmt->bindValue(":password", $hashed_password, SQLITE3_TEXT);
        $stmt->bindValue(":role", $role, SQLITE3_TEXT);
        if($stmt->execute()){
            header("Location: login.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>Register</title>
</head>
<body>
	<div class="login-container">
        <h1>SIGN UP</h1>
        <form method=post>
        <div class="input-group">
            <label for="text">First Name</label>
            <input type="text" name="firstName" placeholder="First Name">
        </div>
        
        <div class="input-group">
            <label for="text">Second Name</label>
            <input type="text" name="secondName" placeholder="Second Name"require>
        </div>
        
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Username@email.com" require>
        </div>
        <!-- <div class="divider">MAKE SURE NO ONE IS BEHIND YOU</div> -->
        <div class="input-group">
            <label for="text">Username</label>
            <input type="text" name="username" placeholder="Username" require>
        </div>
        
        <div class="input-group">
            <label for="password">PASSWORD</label>
            <input type="password" name="password" placeholder="••••••••" require>
        </div>
        
        <div class="input-group">
            <label for="password">CONFIRM PASSWORD</label>
            <input type="password" name="confirm_password" placeholder="••••••••" require>
        </div>
        <?php
            if(isset($_SESSION['errors'])){
                echo "<div class=error-msg>". $_SESSION['errors'] ."</div>";
            }
            unset($_SESSION['errors']);
        ?>
        <button type="submit" name="submit">Register</button>
        </form>
        
        <div class="footer">
            Go back to <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>