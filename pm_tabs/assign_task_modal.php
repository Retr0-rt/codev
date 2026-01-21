<?php
    // Get details for the modal header
    $target_dev_name = "Unknown Developer";
    $target_proj_name = "Unknown Project";

    // Simple lookup for display (User data is already in $team_members or we fetch it)
    // For efficiency, we'll just query the specific user if needed, or rely on IDs.
    // Here we assume IDs are valid.
?>

<div class="form-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
    
    <div class="form-content" style="background: white; padding: 2rem; width: 450px; border: 2px solid #000; box-shadow: 8px 8px 0px #000;">
        
        <h2 style="margin-top: 0; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; text-transform: uppercase;">
            New Task
        </h2>
        
        <form method="POST" action="">
            
            <input type="hidden" name="project_id" value="<?= htmlspecialchars($_GET['view_team']) ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_GET['user_id']) ?>">
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">Task Name</label>
                <input type="text" name="task_name" required 
                       style="width: 100%; padding: 10px; border: 1px solid #000; background: #f9f9f9;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Description</label>
                <textarea name="description" style="width: 100%; padding: 8px; height: 80px;" required></textarea>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">Deadline</label>
                <input type="date" name="deadline" required 
                       style="width: 100%; padding: 10px; border: 1px solid #000; background: #f9f9f9;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 10px;">
                
                <a href="?page=home&view_team=<?= $_GET['view_team'] ?>" class="btn" 
                   style="background: #fff; color: #000; border: 1px solid #000; padding: 10px 20px; text-decoration: none; font-weight: bold;">
                   CANCEL
                </a>

                <button type="submit" name="submit_task" class="btn" 
                        style="background: var(--pm-color); color: #000; border: 1px solid #000; padding: 10px 20px; font-weight: bold; cursor: pointer;">
                    ASSIGN TASK
                </button>
                
            </div>
        </form>
    </div>
</div>