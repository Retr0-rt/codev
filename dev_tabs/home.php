<?php
    $dev_id = $_SESSION['user_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task_status'])) {
        $task_id_to_update = $_POST['task_id'];
        $new_status = $_POST['next_status']; 
    
        update_task_status($task_id_to_update, $dev_id, $new_status);
        header("Location: ?page=home");
        exit();
        
    }

    // part li lfo9 dyal ila saliti chi tas ou bghiti dir "Set as finished"

    // had part dyal informations display kanfetchihom men database ou kanb9aw n'affichiw f table
    $my_projects = get_dev_projects($dev_id);
    
    $selected_project_id = isset($_GET['view_team']) ? $_GET['view_team'] : null;
    $selected_project_name = "";
    $teammates = [];

    if($selected_project_id){
        foreach($my_projects as $p){
            if($p['project_id'] == $selected_project_id){
                $selected_project_name = $p['name'];
                $teammates = get_devs_by_project($selected_project_id);
                break;
            }
        }
    }

    $filter_status = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'All';
    $my_tasks = get_dev_tasks_filtered($dev_id, $filter_status);
?>

<div class="dev-grid-container">

    <div class="dev-left-column">
        <div class="dev-card">
            <h3>My Projects</h3>
            <div class="scrollable-table">
                <table>
                    <thead><tr><th>PROJECT NAME</th></tr></thead>
                    <tbody>
                        <?php if(empty($my_projects)): ?>
                            <tr><td style="text-align:center; padding:15px; color:#777;">No projects yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($my_projects as $proj): ?>
                            <tr style="<?= $selected_project_id == $proj['project_id'] ? 'background-color: #f0f0f0;' : '' ?>">
                                <td style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: bold;"><?= htmlspecialchars($proj['name']) ?></span>
                                    <a href="?page=home&view_team=<?= $proj['project_id'] ?>" 
                                       class="btn" 
                                       style="width: auto; padding: 5px 15px; font-size: 0.8rem; background: var(--primary); color: #fff; box-shadow: none;">SELECT</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dev-card">
            <h3>Coworkers <span style="font-size:0.7em; color:#555;"><?= $selected_project_name ? "($selected_project_name)" : "" ?></span></h3>
            <div class="scrollable-table">
                <table>
                    <thead><tr><th>NAME</th><th>USERNAME</th><th>EMAIL</th></tr></thead>
                    <tbody>
                        <?php if(!$selected_project_id): ?>
                             <tr><td colspan="2" style="text-align:center; padding: 20px; color:#999;">Select a project above.</td></tr>
                        <?php elseif(empty($teammates)): ?>
                             <tr><td colspan="2" style="text-align:center; padding: 20px; color:#999;">No other members.</td></tr>
                        <?php else: ?>
                            <?php foreach($teammates as $member): ?>
                            <tr style="<?= $member['user_id'] == $dev_id ? 'background: #eee; font-weight: bold;' : '' ?>">
                                <td><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></td>
                                <td><?= htmlspecialchars($member['username']) ?></td>
                                <td><?= htmlspecialchars($member['email'])?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="dev-right-column">
        <div class="dev-card">
            <div style="padding: 15px 20px; border-bottom: 3px solid #000; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="border:none; padding:0; margin:0;">MY TASKS</h3>
                <form method="GET">
                    <input type="hidden" name="page" value="home">
                    <?php if($selected_project_id): ?><input type="hidden" name="view_team" value="<?= $selected_project_id ?>"><?php endif; ?>
                    <select name="status_filter" onchange="this.form.submit()" style="padding: 5px; border: 2px solid #000; font-weight: bold;">
                        <option value="All" <?= $filter_status == 'All' ? 'selected' : '' ?>>FILTER: ALL</option>
                        <option value="To Do" <?= $filter_status == 'To Do' ? 'selected' : '' ?>>TO DO</option>
                        <option value="In Progress" <?= $filter_status == 'In Progress' ? 'selected' : '' ?>>IN PROGRESS</option>
                        <option value="Done" <?= $filter_status == 'Done' ? 'selected' : '' ?>>DONE</option>
                    </select>
                </form>
            </div>

            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>TASK / PROJECT</th>
                            <th>DEADLINE</th>
                            <th style="text-align: center;">STATUS</th>
                            <th style="text-align: right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($my_tasks)): ?>
                            <tr><td colspan="4" style="text-align:center; padding: 40px; color: #777;">No tasks found.</td></tr>
                        <?php else: ?>
                            <?php foreach($my_tasks as $task): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:900; font-size: 1rem;"><?= htmlspecialchars($task['title']) ?></div>
                                    <div style="font-size: 0.8rem; color: #555;"><?= htmlspecialchars($task['project_name']) ?></div>
                                </td>
                                <td style="font-weight: bold; color: #d9534f;"><?= date('d M', $task['deadline']) ?></td>
                                <td style="text-align: center;">
                                    <?php 
                                        $sClass = 'status-pending';
                                        if($task['status'] === 'In Progress') $sClass = 'status-in-progress';
                                        if($task['status'] === 'Done') $sClass = 'status-completed';
                                    ?>
                                    <span class="status-badge <?= $sClass ?>"><?= htmlspecialchars($task['status']) ?></span>
                                </td>
                                <td style="text-align: right;">
                                    <button onclick='openTaskModal(
                                                <?= $task['task_id'] ?>, 
                                                "<?= addslashes(htmlspecialchars($task['title'])) ?>", 
                                                "<?= addslashes(htmlspecialchars($task['project_name'])) ?>", 
                                                "<?= date('d M, Y', $task['deadline']) ?>", 
                                                "<?= addslashes($task['description']) ?>", 
                                                "<?= $task['status'] ?>"
                                            )'
                                            class="btn" 
                                            style="width: auto; padding: 8px 15px; background: #000; color: #fff; border: 2px solid #000; box-shadow: none;">
                                        VIEW
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
</div>

<div id="taskModal" class="modal-overlay">
  <div class="modal-content-brutal">
    <span class="close-modal" onclick="closeTaskModal()">&times;</span>
    
    <h3 style="background-color: #ffffff; padding: 15px 20px; margin: 0; font-size: 1.1rem; font-weight: 900; text-transform: uppercase; border-bottom: 3px solid #000000; letter-spacing: 1px;">
        TASK DETAILS
    </h3>
    
    <div style="padding: 25px;">
        <h1 id="modalTitle" style="margin-top: 0; font-weight: 900; font-size: 1.8rem;"></h1>
        
        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div><strong>Project:</strong> <span id="modalProject"></span></div>
            <div><strong>Deadline:</strong> <span id="modalDeadline" style="color: #d9534f; font-weight: bold;"></span></div>
            <?php 
                $modalStatusClass = 'status-pending';
                if($task['status'] === 'In Progress') $modalStatusClass = 'status-in-progress';
                if($task['status'] === 'Done') $modalStatusClass = 'status-completed';
            ?>
             <div><strong>Status:</strong> <span class="<?= $modalStatusClass ?>" id="modalStatusDisplay" style="font-weight: bold;"></span></div>
        </div>
        
        <hr style="border: 1px solid #000;">
        
        <div style="margin: 20px 0;">
            <strong>Description:</strong>
            <p id="modalDescription" style="white-space: pre-wrap; background: #f4f4f4; padding: 15px; border: 2px solid #000;"></p>
        </div>

        <div style="text-align: right; margin-top: 30px;">
            <form method="POST">
                <input type="hidden" name="update_task_status" value="1">
                <input type="hidden" id="modalTaskId" name="task_id" value="">
                <input type="hidden" id="modalNextStatus" name="next_status" value="">
                
                <button type="submit" id="modalActionButton" class="brutal-btn-action" style="display: none;">
                    ACTION BUTTON
                </button>
             </form>
        </div>
    </div>
  </div>
</div>

<script>

// hna sti3mal dyal javascript machi 7it mssawb b Ai 7it deja kankhdem ou biha ou mab9ach lia lwe9t bach nkhdem php lmodals
// 3awtani pi 7ta bach manb9awch khdamin b GET f modals katkheli dak URL twil ou kayfrech lina ch7al mn 7aja f lhistory dyal browser
const modal = document.getElementById('taskModal');
const btnAction = document.getElementById('modalActionButton');
const nextStatusInput = document.getElementById('modalNextStatus');

function openTaskModal(id, title, project, deadline, description, currentStatus) {
    document.getElementById('modalTaskId').value = id;
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalProject').innerText = project;
    document.getElementById('modalDeadline').innerText = deadline;
    document.getElementById('modalDescription').innerText = description;
    document.getElementById('modalStatusDisplay').innerText = currentStatus;

    
    if (currentStatus === 'To Do') {
        btnAction.innerText = "START TASK >";
        btnAction.style.backgroundColor = "#3498db";
        btnAction.style.display = "inline-block";
        nextStatusInput.value = "In Progress";
    } 
    else if (currentStatus === 'In Progress') {
        btnAction.innerText = "SET AS FINISHED ✓";
        btnAction.style.backgroundColor = "#27ae60"; 
        btnAction.style.display = "inline-block";
        nextStatusInput.value = "Done";
    } 
    else {
        btnAction.style.display = "none";
    }

    modal.style.display = "block";
}

function closeTaskModal() {
    modal.style.display = "none";
}


window.onclick = function(event) {
    if (event.target == modal) {
        closeTaskModal();
    }
}
</script>