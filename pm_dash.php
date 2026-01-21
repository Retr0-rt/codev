<?php
    session_start();
    if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'Project Manager'){
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
    <title>Project Manager Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary: #1A1A1A;
            --secondary: #FFFFFF; 
            --pm-color: #D4BB47; 
        }
        
        .sidebar .brand { color: var(--pm-color); }
        
        .nav-link {
            display: block;
            text-decoration: none;
            color: var(--primary);
            font-weight: bold;
            padding: 15px;
            margin-bottom: 10px;
            border: 2px solid transparent;
            transition: all 0.2s;
            }

            .nav-link:hover, .nav-link.active {
            background-color: var(--pm-color);
            color: var(--secondary);
            border: 2px solid var(--primary);
            box-shadow: 4px 4px 0px var(--primary);
            }

        /* Layout Structure */
        .pm-container {
            display: flex; 
            flex-direction: column; 
            height: calc(100vh - 40px);
            gap: 20px;
        }

        .pm-top-section {
            flex: 1; 
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            min-height: 0; 
        }

        .pm-bottom-section {
            flex: 1; 
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        /* STYLING FIX: Black Borders & Sharp Edges */
        .pm-card {
            background: white;
            border: 2px solid #000; 
            box-shadow: 4px 4px 0px #000; 
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        .pm-card h3, .pm-card h2 {
            padding: 15px;
            margin: 0;
            border-bottom: 2px solid #000; 
            background: #fff;
            flex-shrink: 0;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Table Styling fixes */
        .scrollable-table {
            flex-grow: 1;
            overflow-y: auto;
        }
        
        .scrollable-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .scrollable-table th {
            position: sticky;
            top: 0;
            background: #f0f0f0;
            z-index: 1;
            border-bottom: 2px solid #000;
            text-align: left;
            padding: 12px;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .scrollable-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <aside class="sidebar">
            <div class="brand">CoDev PM</div>
            <nav>
                <a href="?page=home" class="nav-link <?= $page === 'home' ? 'active' : '' ?>">My Projects</a>
                <a href="logout.php" class="nav-link">Log Out</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php
                $tab_path = "pm_tabs/{$page}.php";
                if (file_exists($tab_path)){
                    include($tab_path);
                }else{
                    echo "<h1 class='error-msg'>Page not found</h1>";
                }
            ?>
        </main>
    </div>

</body>
</html>