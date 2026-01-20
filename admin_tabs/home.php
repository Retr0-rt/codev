<?php
    require "Includes/functions.php";
    $db = get_db_connection();
    $devs = get_count($db, "Users", "role = 'Developer'");
    $projects = get_count($db, "Projects", 1);
    $pms = get_count($db, "Users", "role = 'Project Manager'");
    $logs = get_logs(6);
    //  post_log($db, "helko l3ya baghi conji", "slak_ajmi");
?>

<h1 class="header-title">Overview</h1>
<div class="stats-grid">
    <div class="card">
        <h3>Total Developers</h3>
        <div class="number"><?= $devs ?></div>
    </div>
    <div class="card">
        <h3>Active Projects</h3>
        <div class="number"><?= $projects ?></div>
    </div>
    <div class="card">
        <h3>Project Managers</h3>
        <div class="number"><?= $pms ?></div>
    </div>
</div>

<div class="activity-section">
    <h2>Recent Activity</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User / Project</th>
                    <th>Action</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($logs as $log){
                        echo "<tr>
                        <td><strong>".$log['mol_laction']."</strong></td>
                        <td>".$log['action']."</td>
                        <td>".format_time_ago($log['created_at'])."</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>