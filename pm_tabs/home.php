<?php
    require_once "Includes/functions.php";

    $pm_id = $_SESSION['user_id'];
    
    $my_projects = get_pm_projects($pm_id);

    $selected_project_id = isset($_GET['view_team']) ? $_GET['view_team'] : null;
    $team_members = [];
    $selected_project_name = "Select a Project";

    if($selected_project_id){
        foreach($my_projects as $p){
            if($p['project_id'] == $selected_project_id){
                $selected_project_name = $p['name'];
                $team_members = get_devs_by_project($selected_project_id);
                break;
            }
        }
    }

    $all_tasks = get_pm_tasks($pm_id); 
?>

<div class="pm-container">

    <div class="pm-top-section">
        
        <div class="pm-card">
            <h3>My Projects</h3>
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($my_projects as $proj): ?>
                        <tr style="<?= $selected_project_id == $proj['project_id'] ? 'background-color: #fffde7;' : '' ?>">
                            <td>
                                <div style="font-weight: bold; font-size: 1rem; color: #000;">
                                    <?= htmlspecialchars($proj['name']) ?>
                                </div>
                                
                                <div style="font-size: 0.8rem; color: #777; margin-top: 4px;">
                                    <?= htmlspecialchars(substr($proj['description'], 0, 45)) . (strlen($proj['description']) > 45 ? '...' : '') ?>
                                </div>
                            </td>
                            
                            <td style="text-align: right; vertical-align: middle;">
                                <div style="display: flex; justify-content: flex-end; gap: 5px; align-items: center;">
                                    
                                    <button class="btn" 
                                    title="Add Developer"
                                    onclick="alert('Add Dev Modal for: <?= $proj['project_id'] ?>')"
                                    style="padding: 4px 10px; font-weight: bold; background: #e0e0e0; color: #000; border: 1px solid #000; cursor: pointer;">
                                    +
                                    </button>

                                    <a href="?page=home&view_team=<?= $proj['project_id'] ?>" 
                                    class="btn" 
                                    style="padding: 4px 10px; background: var(--pm-color); color: #000; font-weight: bold; border: 1px solid #000; text-decoration: none; font-size: 0.85rem; display: inline-block;">
                                    TEAM
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($my_projects)): ?>
                            <tr><td colspan="2" style="text-align:center; padding:20px; color: #777;">No projects assigned.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pm-card">
            <h3>Team: <span style="color: var(--pm-color);"><?= htmlspecialchars($selected_project_name) ?></span></h3>
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>Developer</th>
                            <th style="text-align: right;">Assign</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($team_members) && $selected_project_id): ?>
                             <tr><td colspan="2" style="text-align:center; padding: 20px; color:#999;">No developers assigned yet.</td></tr>
                        <?php elseif(!$selected_project_id): ?>
                             <tr><td colspan="2" style="text-align:center; padding: 20px; color:#999;">Select a project to view the team.</td></tr>
                        <?php else: ?>
                            <?php foreach($team_members as $dev): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($dev['first_name'] . ' ' . $dev['last_name']) ?></strong>
                                </td>
                                <td style="text-align: right;">
                                    <button class="btn" 
                                            style="padding: 5px 10px; font-size: 0.75rem; background: #333; color: #fff; border: 1px solid #000;">
                                       TASK
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="pm-bottom-section">
        <div class="pm-card">
            <h2>Current Tasks & Deadlines</h2>
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_tasks as $task): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($task['task_name']) ?></strong></td>
                            <td><?= htmlspecialchars($task['dev_first'] . ' ' . $task['dev_last']) ?></td>
                            <td><?= htmlspecialchars($task['project_name']) ?></td>
                            <td>
                                <?php 
                                    $statusColor = '#ccc';
                                    if($task['status'] == 'Pending') $statusColor = '#f39c12';
                                    if($task['status'] == 'In Progress') $statusColor = '#3498db';
                                    if($task['status'] == 'Completed') $statusColor = '#27ae60';
                                ?>
                                <span style="padding: 3px 8px; border: 1px solid #000; background: <?= $statusColor ?>; color: white; font-size: 0.75rem; font-weight: bold;">
                                    <?= htmlspecialchars($task['status']) ?>
                                </span>
                            </td>
                            <td style="color: #d9534f; font-weight: bold;">
                                <?= htmlspecialchars($task['deadline']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($all_tasks)) echo "<tr><td colspan='5' style='text-align:center; padding:20px;'>No active tasks.</td></tr>"; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>