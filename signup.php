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
            <input type="text" id="firstName" placeholder="First Name">
        </div>
        
        <div class="input-group">
            <label for="text">Second Name</label>
            <input type="text" id="secondName" placeholder="Second Name"require>
        </div>
        
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" placeholder="Username@email.com" require>
        </div>
        <!-- <div class="divider">MAKE SURE NO ONE IS BEHIND YOU</div> -->
        <div class="input-group">
            <label for="text">Username</label>
            <input type="text" id="secondName" placeholder="Username" require>
        </div>
        
        <div class="input-group">
            <label for="password">PASSWORD</label>
            <input type="password" id="password" placeholder="••••••••" require>
        </div>
        
        <div class="input-group">
            <label for="password">CONFIRM PASSWORD</label>
            <input type="password" id="confirm_password" placeholder="••••••••" require>
        </div>
        
        <button type="submit">Register</button>
        </form>
        
        <div class="footer">
            Go back to <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
<?php
    $db = new SQLite3("codev.db");
    
?>