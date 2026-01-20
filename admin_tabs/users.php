<?php
    require_once "Includes/functions.php";
    
    $showForm = false; 
    $formData = ['user_id'=> '', 'username'=>'', 'first_name'=>'', 'last_name'=>'', 'email'=>'', 'role'=>'', 'password'=>''];
    $formTitle = "ADD NEW USER";
    if(isset($_GET['action'])){
        if($_GET['action'] === 'add_user'){
            $showForm = true;
        }
        elseif ($_GET['action'] === 'edit_user'){
            $showForm = true;
            $formData = get_user_by_id($_GET['id']);
            $formTitle = "UPDATE USER";
        }
    }

    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $users = get_users($search);
?>
<?php
    if($showForm){
        include("user_form_modal.php");
    }
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 3px solid var(--primary); padding-bottom: 20px;">
    <h1 style="margin: 0; text-align: left;">USER MANAGEMENT</h1>
    
    <a href="?page=users&action=add_user" class="btn" style="width: auto; padding: 10px 20px; margin: 0;">
        + ADD NEW USER
    </a>
</div>

<div class="toolbar" style="margin-bottom: 30px;">
    <form method="GET" action="admin_dash.php" style="display: flex; gap: 15px;">
        <input type="hidden" name="page" value="users">
        
        <div style="flex-grow: 1;">
            <input type="text" 
                   name="search" 
                   placeholder="Search users..." 
                   value="<?= htmlspecialchars($search) ?>"
                   style="margin: 0;">
        </div>

        <button type="submit" style="width: auto; margin: 0; padding: 0 30px;">
            SEARCH
        </button>
        
        <?php if($search): ?>
            <a href="admin_dash.php?page=users" class="btn" style="width: auto; margin: 0; background: #ccc; color: #000;">
                CLEAR
            </a>
        <?php endif; ?>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Username</th>
                <th style="width: 20%;">Full Name</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 15%;">Role</th>
                <th style="width: 15%; text-align: right;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                
                <td>
                    <?php 
                        $badgeClass = 'badge-dev';
                        if ($user['role'] === 'Admin') $badgeClass = 'badge-admin';
                        if ($user['role'] === 'Project Manager') $badgeClass = 'badge-pm';
                        ?>
                    <span class="badge <?= $badgeClass ?>" 
                          style="padding: 4px 8px; border: 1px solid #000; font-size: 0.8rem; font-weight: bold;">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </td>

                <td style="text-align: right;">
                    <a href="?page=users&action=edit_user&id=<?= $user['user_id'] ?>" 
                       style="text-decoration: none; color: #000; font-weight: bold; margin-right: 10px;">
                       EDIT
                    </a>
                    
                    <a href="?page=delete_user&id=<?= $user['user_id'] ?>" 
                       style="text-decoration: none; color: #ff4444; font-weight: bold;"
                       onclick="return confirm('Are you sure you want to delete this user?');">
                       DELETE
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($users)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        No users found matching "<?= htmlspecialchars($search) ?>"
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
