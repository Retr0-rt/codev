<?php
    session_start();
    require_once "Includes/functions.php";

    // 1. SECURITY: Strict Access Control
    if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'Developer'){
        header("Location: login.php");
        exit();
    }
    
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* THEME OVERRIDES */
        :root {
            /* Overriding variables for this page specifically b7al dakchi dyal inheritance ou kda */
            --accent: #3b3b3b;      
            --complementary: #1a1a1a; 
        }
        
        /* Sidebar Brand Color */
        .sidebar .brand { color: var(--secondary); background: var(--primary); padding: 10px; text-align: center; }
    
        .nav-link.active {
            background-color: var(--accent);
            color: #fff;
            border-color: #000;
            box-shadow: 4px 4px 0px #000;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <aside class="sidebar">
            <div class="brand">DEV_CONSOLE</div>
            <nav>
                <a href="?page=home" class="nav-link <?= $page === 'home' ? 'active' : '' ?>" style="margin-bottom: 20px;">Workspace</a>
                <a href="logout.php" class="nav-link logout-link">Log Out</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php

                // 7imayat l website mn LFI attack cybersecurity rah kayna 
                $allowed_pages = ['home']; 
                
                if(in_array($page, $allowed_pages)){
                    $tab_path = "dev_tabs/{$page}.php";
                    
                    if (file_exists($tab_path)){
                        include($tab_path);
                    } else {
                        echo "<div class='pm-card'><h3>Error 404</h3><p>Tab file not found: $tab_path</p></div>";
                    }
                } else {
                    echo "<div class='pm-card'><h3>Access Denied</h3><p>Invalid page request.</p></div>";
                }
            ?>
        </main>
    </div>

</body>
</html>