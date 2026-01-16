
<?php
    session_start();
    $db = new SQLite3("codev.db");
    $error = "";

    if(isset($_POST["submit"]))
    {
        $username = $_POST["username"];
        $pass = $_POST["password"];

        // i used this way of login validation instead of the classic way to prevent from sql injection attacks 
        // because we prepare the whole statement before giving it to the database
        // so bind will replace username ('or 1=1 in case of sqli attack) with literal "or 1=1" string
        $query = "select * from users where username = :user";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':user', $username, SQLITE3_TEXT);
        
        $response = $stmt->execute();
        $user_info = $response->fetchArray(SQLITE3_ASSOC);
        if(password_verify($pass, $user_info["password_hash"])){
            // here i prepared the user session for future accessibility checks
            // this is the thing that will prevent dev from accessing admin dashboard for example
            $_SESSION['user_id'] = $user_info['user_id'];
            $_SESSION['username'] = $user_info['username'];
            $_SESSION['role'] = $user_info['role'];

            switch($_SESSION['role']){
                case 'Admin':
                    header("Location: admin_dash.php");
                    exit();
                case 'Project Manager':
                    header("Location: admin_dash.php");
                    exit();
                case 'Developer':
                    header("Location: dev_dash.php");
                    exit();
                default:
                    $error = "Role not found";
                    exit();
            }
        } else{
            $_SESSION['flash_error'] = "Wrong username or password :(";
            header("Location: login.php"); // flash_error method to prevent browser resubmission problem
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
	<title>Login</title>
</head>
<body>
	<div class="login-container">
        <h1>LOGIN</h1>

        <form method=post>
            <div class="input-group">
                <label for="text">Username</label>
                <input type="text" name="username" id="username" placeholder="slak ajmi" required>
            </div>
            
            <div class="input-group">
                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>
            <?php if(isset($_SESSION['flash_error'])){
                echo '<div class="error-msg"> '. $_SESSION['flash_error'] . '</div>';
            }
            unset($_SESSION['flash_error']);
            ?>
            <button type="submit" name="submit" href="dashboard.php">SIGN IN</button>
        </form>
        <div class="divider">OR</div>
        
        
        <div class="footer">
            Don't have an account? <a href="signup.php">Sign up</a>
        </div>
    </div>
</body>
</html>