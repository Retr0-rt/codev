<?php
    require_once "Includes/functions.php";
    $users = get_users($_GET['search']);
?>

<h1 class="header-title">USER MANAGEMENT</h1>
<div class="stats-grid">
    <div class="card">
        <h3>ADD USER</h3>
        <div class="number"><?= $devs ?></div>
    </div>
    <div class="card">
        <h3>UPDATE USER</h3>
        <div class="number"><?= $projects ?></div>
    </div>
    <div class="card">
        <h3>DELETE USER</h3>
        <div class="number"><?= $pms ?></div>
    </div>
</div>

<div class="activity-section">
    <div class="header-actions" style="display: flex; gap: 10px; margin-bottom: 20px;">
    
    <form method="GET" action="admin_dash.php" style="display: flex; width: 100%; gap: 10px;">
        
        <input type="hidden" name="page" value="users">
        
        <input type="text" 
               name="search" 
               placeholder="Search by name, username, or email..." 
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
               style="flex-grow: 1;">
        
        <button type="submit">SEARCH</button>
        
        <?php if(isset($_GET['search'])): ?>
            <a href="admin_dash.php?page=users" class="btn" style="background: #ccc; color: #000;">X</a>
        <?php endif; ?>
        
    </form>
    
</div>  
    <h2>Users</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Full name</th>
                    <th>Username</th>
                    <th>email</th>
                    <th>role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($users as $user){
                        echo "<tr>
                        <td><strong>".$user['first_name']." ".$user['last_name']."</strong></td>
                        <td>".$user['username']."</td>
                        <td>".$user['email']."</td>
                        <td>".$user['role']."</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>