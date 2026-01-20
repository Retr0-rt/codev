<?php

    // had part dyal projects sahla just copy paste from users.php w bdel les variables w smiyat dyal functions

    require_once "Includes/functions.php";
    
    $showForm = false; 

    $formData = ['project_id'=> '', 'name'=>'', 'description'=>'', 'pm_id'=>''];
    $formTitle = "ADD NEW PROJECT";

    if(isset($_GET['action'])){
        if($_GET['action'] === 'add_project'){
            $showForm = true;
        }
        elseif ($_GET['action'] === 'edit_project'){
            $showForm = true;
            $formData = get_project_by_id($_GET['id']);
            $formTitle = "UPDATE PROJECT";
        }
    }

    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $projects = get_projects($search);
?>

<?php
    if($showForm){
        include("project_form_modal.php");
    }
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 3px solid var(--primary); padding-bottom: 20px;">
    <h1 style="margin: 0; text-align: left;">PROJECTS</h1>
    
    <div style="display: flex; gap: 10px;">
        <a href="?page=projects&action=add_project" class="btn" style="width: auto; padding: 10px 20px; margin: 0;">
            + ADD NEW PROJECT   
        </a>
    </div>
</div>

<div class="toolbar" style="margin-bottom: 30px;">
    <form method="GET" action="admin_dash.php" style="display: flex; gap: 15px;">
        <input type="hidden" name="page" value="projects">
        
        <div style="flex-grow: 1;">
            <input type="text" 
                   name="search" 
                   placeholder="Search projects..." 
                   value="<?= htmlspecialchars($search) ?>"
                   style="margin: 0;">
        </div>

        <button type="submit" style="width: auto; margin: 0; padding: 0 30px;">
            SEARCH
        </button>
        
        <?php if($search): ?>
            <a href="admin_dash.php?page=projects" class="btn" style="width: auto; margin: 0; background: #ccc; color: #000;">
                CLEAR
            </a>
        <?php endif; ?>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Project Name</th>
                <th style="width: 40%;">Description</th>
                <th style="width: 20%;">Project Manager</th>
                <th style="width: 15%; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $proj): ?>
            <tr>
                <td><strong><?= htmlspecialchars($proj['name']) ?></strong></td>
                
                <td style="color: #666;"><?= htmlspecialchars(substr($proj['description'], 0, 50)) . (strlen($proj['description']) > 50 ? '...' : '') ?></td>
                
                <td>
                    <?php if($proj['pm_first']): ?>
                        <span class="badge badge-pm" style="padding: 4px 8px; border: 1px solid #000; font-size: 0.8rem; font-weight: bold;">
                            <?= htmlspecialchars($proj['pm_first'] . ' ' . $proj['pm_last']) ?>
                        </span>
                    <?php else: ?>
                        <span style="color: #999; font-style: italic;">Unassigned</span>
                    <?php endif; ?>
                </td>

                <td style="text-align: right;">
                    <a href="?page=projects&action=edit_project&id=<?= $proj['project_id'] ?>" 
                       style="text-decoration: none; color: #000; font-weight: bold; margin-right: 10px;">
                       EDIT
                    </a>
                    
                    <a href="?page=delete_project&id=<?= $proj['project_id'] ?>" 
                       style="text-decoration: none; color: #ff4444; font-weight: bold;"
                       onclick="return confirm('Are you sure you want to delete this project?');">
                       DELETE
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($projects)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">
                        No projects found matching "<?= htmlspecialchars($search) ?>"
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>