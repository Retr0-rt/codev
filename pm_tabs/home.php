<?php
    require_once "Includes/functions.php";

    $pm_id = $_SESSION['user_id'];
    
    // --- 1. HANDLE POST REQUESTS (Forms) ---
    
    // A. Add Developer
    if(isset($_POST['assign_dev_submit'])){
        assign_user_to_project($_POST['user_id'], $_POST['project_id']); 
        header("Location: ?page=home&view_team=" . $_POST['project_id']);
        exit();
    }

    // B. Assign Task
    if(isset($_POST['submit_task'])){
        create_task($_POST['task_name'], $_POST['description'], $_POST['project_id'], $_POST['user_id'], $_POST['deadline']);
        header("Location: ?page=home&view_team=" . $_POST['project_id']);
        exit();
    }

    // Remove Member Action
    if(isset($_GET['action']) && $_GET['action'] === 'remove_member'){
        remove_user_from_project($_GET['user_id'], $_GET['view_team']);
        header("Location: ?page=home&view_team=" . $_GET['view_team']);
        exit();
    }
    // remove task
    if(isset($_GET['action']) && $_GET['action'] === 'delete_task'){
        delete_task($_GET['task_id']);
        $redirect = "?page=home" . (isset($_GET['view_team']) ? "&view_team=".$_GET['view_team'] : "");
        header("Location: $redirect");
        exit();
    }
    $my_projects = get_pm_projects($pm_id);

    // Selected Project Logic
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
    
    // Modals Triggers
    $showAssignModal = (isset($_GET['action']) && $_GET['action'] === 'assign_dev');
    $showTaskModal   = (isset($_GET['action']) && $_GET['action'] === 'assign_task');
    
    if($showAssignModal){
        $available_devs = get_available_devs_for_project($_GET['project_id']);
    }
?>

<?php if($showAssignModal) include("assign_dev_modal.php"); ?>
<?php if($showTaskModal) include("assign_task_modal.php"); ?>

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
                                    <a href="?page=home&view_team=<?= $proj['project_id'] ?>" 
                                       class="btn" 
                                       style="padding: 4px 10px; background: var(--pm-color); color: #000; font-weight: bold; border: 1px solid #000; text-decoration: none; font-size: 0.85rem; display: inline-block;">
                                       TEAM
                                    </a>
                                    <a href="?page=home&action=assign_dev&project_id=<?= $proj['project_id'] ?>&view_team=<?= $proj['project_id'] ?>" 
                                       class="btn" title="Add Developer"
                                       style="padding: 4px 10px; font-weight: bold; font-size: 0.85rem; background: #e0e0e0; color: #000; border: 1px solid #000; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                                       +
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($my_projects)) echo "<tr><td colspan='2' style='text-align:center; padding:20px; color: #777;'>No projects assigned.</td></tr>"; ?>
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
                            <th style="text-align: right;">Actions</th>
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
                                    <?= htmlspecialchars($dev['first_name'] . ' ' . $dev['last_name']) ?> <br>
                                    <span style="font-size: 0.8rem; color: #666;">aka <strong><?= htmlspecialchars($dev["username"]) ?></strong></span>
                                </td>
                                <td style="text-align: right; vertical-align: middle;">
                                    <div style="display: flex; justify-content: flex-end; gap: 5px">
                                        <a href="?page=home&view_team=<?= $selected_project_id ?>&action=assign_task&user_id=<?= $dev['user_id'] ?>" 
                                            class="btn" 
                                            style="padding: 4px 0px; background: #3b3b3b; color: #fff; font-weight: bold; border: 1px solid #000; text-decoration: none; font-size: 0.85rem; display: inline-block;">
                                            TASK
                                        </a>
                                        
                                        <a href="?page=home&view_team=<?= $selected_project_id ?>&action=remove_member&user_id=<?= $dev['user_id'] ?>"
                                           onclick="return confirm('Remove <?= htmlspecialchars(get_dev_by_id($dev['user_id'])['first_name'] . ' ' . get_dev_by_id($dev['user_id'])['last_name']) ?> from this project?');"
                                           class="btn"
                                           title="Remove Member"
                                           style="padding: 4px 0px; display: flex; align-items: center; justify-content: center; background: #ffcccc; color: #d9534f; border: 1px solid #000; font-weight: bold; font-size: 0.85rem; text-decoration: none;">
                                           -
                                        </a>

                                        
                                    </div>
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
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_tasks as $task): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($task['title']) ?></strong></td>
                            <td><?= htmlspecialchars(get_dev_by_id($task['assigned_to'])['first_name'] . ' ' . get_dev_by_id($task['assigned_to'])['last_name']) ?></td>
                            <td><?= htmlspecialchars($task['project_name']) ?></td>
                            <td>
                                <span style="padding: 3px 8px; border: 1px solid #000; background: <?= ($task['status']=='Pending'?'#f39c12':($task['status']=='In Progress'?'#3498db':'#27ae60')) ?>; color: white; font-size: 0.75rem; font-weight: bold;">
                                    <?= htmlspecialchars($task['status']) ?>
                                </span>
                            </td>
                            <td style="color: #d9534f; font-weight: bold;">
                                <?= htmlspecialchars(date('j-M-Y',$task['deadline'])) ?>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <a href="?page=home&action=delete_task&task_id=<?= $task['task_id'] ?><?= isset($_GET['view_team']) ? '&view_team='.$_GET['view_team'] : '' ?>" 
                                onclick="return confirm('Are you sure you want to delete this task?');"
                                title="Delete Task"
                                style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 4px; color: #d9534f; font-weight: bold; text-decoration: none; font-size: 1.2rem; margin: 0 auto;">
                                &times;
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>