<?php
    $managers = get_project_managers();

    if(isset($_POST['submit_project'])){
        $id = $_POST['project_id'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $pm_id = $_POST['pm_id'];
        
        $msg = "Error saving project.";
        
        if(empty($id)){
            create_project($name, $desc, $pm_id);
            $msg = urlencode("Project created successfully.");
        }
        else{
            update_project($id, $name, $desc, $pm_id);
            $msg = urlencode("Project updated successfully.");
        }
        
        $location = isset($search) ? "?page=projects&msg=$msg&search=$search" : "?page=projects&msg=$msg";
        header("Location: $location");
        exit();
    }
?>

<div class="form-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
    
    <div class="form-content" style="background: white; padding: 2rem; width: 500px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        
        <h2 style="margin-top: 0; border-bottom: 1px solid #ccc; padding-bottom: 10px;"><?= $formTitle ?></h2>

        <form method="POST" action="">
            
            <input type="hidden" name="project_id" value="<?= htmlspecialchars($formData['project_id']) ?>">
            
            <div style="margin-bottom: 15px;">
                <label>Project Name</label>
                <input type="text" name="name" style="width: 100%; padding: 8px;"
                    value="<?= htmlspecialchars($formData['name']) ?>" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Description</label>
                <textarea name="description" style="width: 100%; padding: 8px; height: 80px;" required><?= htmlspecialchars($formData['description']) ?></textarea>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label>Project Manager</label>
                <select name="pm_id" style="width: 100%; padding: 8px;" required>
                    <?php if($_GET['action'] === 'add_project'): ?>
                        <option value="">-- Select a Manager --</option>
                    <?php endif; ?>
                    <?php foreach($managers as $pm): ?>
                        <option value="<?= $pm['user_id'] ?>" 
                            <?= $formData['pm_id'] == $pm['user_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pm['first_name'] . ' ' . $pm['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                    
                </select>
            </div>

            <div style="text-align: right; border-top: 1px solid #ccc; padding-top: 15px; display: flex; justify-content: flex-end; gap: 10px;">
                
                <a href="admin_dash.php?page=projects" class="btn" 
                   style="background: #888; color: white; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; min-width: 120px;">
                   CANCEL
                </a>

                <button type="submit" name="submit_project" class="btn" 
                        style="min-width: 120px; display: inline-flex; align-items: center; justify-content: center;">
                    <?= empty($formData['project_id']) ? 'CREATE PROJECT' : 'UPDATE PROJECT' ?>
                </button>
                
            </div>
        </form>
    </div>
</div>