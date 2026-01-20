<?php
    $pass_label = ($_GET["action"] === 'add_user') ? "Password" : "New Password (Optional)";
    if(isset($_POST['submit'])){
        $id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $msg = "A problem occurred while saving the user.";
        if($_GET['action'] === 'add_user'){
            create_user($first_name, $last_name, $username, $email, $password, $role);
            $msg = urlencode("User created successfully.");
        }
        else{
            update_user($id, $first_name, $last_name, $username, $email, $role, $password);
            $msg = urlencode("User updated successfully.");
        }
        $location = isset($search) ? "?page=users&msg=$msg&search=$search" : "?page=users&msg=$msg";
        header("Location: $location");
        exit();
    }
?>
    <div class="form-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
    
        <div class="form-content" style="background: white; padding: 2rem; width: 500px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            
            <h2 style="margin-top: 0; border-bottom: 1px solid #ccc; padding-bottom: 10px;"><?= $formTitle ?></h2>

            <form method="POST" action="">
                
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($formData['user_id']) ?>">
                
                <div style="margin-bottom: 15px;">
                    <label>First Name</label>
                    <input type="text" name="first_name" style="width: 100%; padding: 8px;"
                        value="<?= htmlspecialchars($formData['first_name']) ?>" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Last Name</label>
                    <input type="text" name="last_name" style="width: 100%; padding: 8px;"
                        value="<?= htmlspecialchars($formData['last_name']) ?>" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Username</label>
                    <input type="text" name="username" style="width: 100%; padding: 8px;"
                        value="<?= htmlspecialchars($formData['username']) ?>" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Email</label>
                    <input type="email" name="email" style="width: 100%; padding: 8px;"
                        value="<?= htmlspecialchars($formData['email']) ?>" required>
                </div>
                    
                
                <div style="margin-bottom: 20px;">
                    <label>Role</label>
                    <select name="role" style="width: 100%; padding: 8px;">
                        <option value="Developer" <?= $formData['role'] == 'Developer' ? 'selected' : '' ?>>Developer</option>
                        <option value="Project Manager" <?= $formData['role'] == 'Project Manager' ? 'selected' : '' ?>>Project Manager</option>
                        <option value="Admin" <?= $formData['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label><?= $pass_label ?></label>
                    <input type="password" name="password" style="width: 100%; padding: 8px;">
                </div>

            <div style="text-align: right; border-top: 1px solid #ccc; padding-top: 15px; display: flex; justify-content: flex-end; gap: 10px;">
                
                <button type="submit" name="submit" class="btn" 
                style="min-width: 120px; display: inline-flex; align-items: center; justify-content: center;">
                <?= empty($formData['user_id']) ? 'CREATE USER' : 'UPDATE USER' ?>
                </button>
                <a href="admin_dash.php?page=users" class="btn" 
                style="background: #888; color: white; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; min-width: 120px;">
                CANCEL
                </a>
            </div>
            
        </form>
    </div>
</div>
