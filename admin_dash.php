<?php
    session_start();
    if($_SESSION['role'] !== 'Admin'){
        header("Location: login.php");
    }
    $page =  isset($_GET['page']) ? $_GET['page'] : 'home';
?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoCode Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="dashboard-container">
        
        <aside class="sidebar">
            <div class="brand">CoDev Admin</div>
            <nav>
                <a href="?page=home" class="nav-link active">Dashboard</a>
                <a href="?page=users" class="nav-link">User Management</a>
                <a href="?page=projects" class="nav-link">Projects</a>
                <a href="logout.php" class="nav-link logout">Log Out</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php
                $tab_path = "admin_tabs/{$page}.php";
                if (file_exists($tab_path)){
                    include($tab_path);
                }else{
                    echo "<h1 class='error-msg'>page not found 404</h1>";
                }
            ?>
        </main>
    </div>

</body>
</html>