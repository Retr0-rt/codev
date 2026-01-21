<?php
    $target_project_name = "Unknown Project";
    foreach($my_projects as $p){
        if($p['project_id'] == $_GET['project_id']){
            $target_project_name = $p['name'];
            break;
        }
    }
?>

<div class="form-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
    
    <div class="form-content" style="background: white; padding: 2rem; width: 450px; border: 2px solid #000; box-shadow: 8px 8px 0px #000;">
        
        <h2 style="margin-top: 0; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; text-transform: uppercase;">
            Assign Developer
        </h2>
        
        <p style="margin-bottom: 20px;">
            Adding member to: <strong><?= htmlspecialchars($target_project_name) ?></strong>
        </p>

        <form method="POST" action="">
            
            <input type="hidden" name="project_id" value="<?= htmlspecialchars($_GET['project_id']) ?>">
            
            <div style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 8px;">Select Developer</label>
                <select name="user_id" style="width: 100%; padding: 10px; border: 1px solid #000; background: #f9f9f9;" required>
                    <option value="">-- Choose a Developer --</option>
                    <?php if(empty($available_devs)): ?>
                        <option value="" disabled>No available developers found.</option>
                    <?php else: ?>
                        <?php foreach($available_devs as $dev): ?>
                            <option value="<?= $dev['user_id'] ?>">
                                <?= htmlspecialchars($dev['first_name'] . ' ' . $dev['last_name'])?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 10px;">
                
                <a href="?page=home&view_team=<?= $selected_project_id ?>" class="btn" 
                   style="background: #fff; color: #000; border: 1px solid #000; padding: 10px 20px; text-decoration: none; font-weight: bold;">
                   CANCEL
                </a>

                <button type="submit" name="assign_dev_submit" class="btn" 
                        style="background: var(--pm-color); color: #000; border: 1px solid #000; padding: 10px 20px; font-weight: bold; cursor: pointer;">
                    ADD MEMBER
                </button>
                
            </div>
        </form>
    </div>
</div>