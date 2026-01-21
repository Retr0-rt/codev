<?php
    function get_db_connection(){
        $pdo = new PDO('sqlite:codev.db');
        if(!$pdo){
            die("Database connection failed");
        }
        return $pdo;
    }
    function get_count($pdo, $table, $condition){
        $stmt = $pdo->prepare("Select count(*) as count from $table where $condition");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    function post_log($pdo, $action, $username){
        $stmt = $pdo->prepare("insert into activity_logs(mol_laction, action, created_at) 
        values(:user,:action,:timestamp);");
        $stmt->bindValue(':user', $username);
        $stmt->bindValue(':action', $action);
        $stmt->bindValue(':timestamp', time());
        $stmt->execute();
    }

    function get_logs($number){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("Select * from activity_logs order by created_at desc limit $number;");
        $response = $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function get_user_by_id($user_id){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * from users where user_id = :user_id");
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function create_user($first_name, $last_name, $username, $email, $pass, $role){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("INSERT into users(first_name, last_name, username, email, password_hash, role) 
        values(:first_name, :last_name, :username, :email, :password, :role);");
        $stmt->bindValue(":first_name", $first_name);
        $stmt->bindValue(":last_name", $last_name);
        $stmt->bindValue(":username", $username);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", password_hash($pass, PASSWORD_DEFAULT));
        $stmt->bindValue(":role", $role);
        $stmt->execute();
        return $pdo->lastInsertId();
    }
    function update_user($id, $first_name, $last_name, $username, $email, $role, $password = null){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("UPDATE users set first_name = :first_name, last_name = :last_name, email = :email, role = :role 
        ,username = :username where user_id = :id;");
        $stmt->bindValue(":first_name", $first_name);
        $stmt->bindValue(":last_name", $last_name);
        $stmt->bindValue(":username", $username);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":role", $role);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        if($password){
            $stmt = $pdo->prepare("UPDATE users set password_hash = :pass where user_id = :id;");
            $stmt->bindValue(":id", $id);
            $stmt->bindValue(":pass", password_hash($password, PASSWORD_DEFAULT));
            $stmt->execute();
        }
    }

    function get_users($search = null) {
    $pdo = get_db_connection();

    if ($search) {
        // We use % wildcard for partial matches dakchi machi search by column walakin 9adi gharad
        $sql = "SELECT * from users 
                where username like :search 
                or email like :search 
                or first_name like :search 
                or last_name like :search
                order by
                case
                when role = 'Admin' then 0
                when role = 'Project Manager' then 1
                when role = 'Developer' then 2
                else 99 end asc";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
    } 
    // Oprtion B: la maknch chi seach kankharjo kolchi
        $sql = "SELECT * FROM users
        order by
        case
        when role = 'Admin' then 0
        when role = 'Project Manager' then 1
        when role = 'Developer' then 2
        else 99 end asc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//partia dyal CRUD walakin had lmra 3la lprojyat

function get_project_managers() {
    $pdo = get_db_connection();
    $stmt = $pdo->query("SELECT user_id, first_name, last_name FROM users WHERE role = 'Project Manager' ORDER BY first_name ASC");
    return $stmt->fetchAll();
}

// les jointures tel3o mla7. joins here used to show the name of the PM in the table just by the foreign key in the projects table
function get_projects($search = null) {
    $pdo = get_db_connection();
    $sql = "SELECT p.*, u.first_name as pm_first, u.last_name as pm_last 
            FROM projects p 
            LEFT JOIN users u ON p.pm_id = u.user_id";
            
    if ($search) {
        $sql .= " WHERE p.name like :s OR p.description LIKE :s or pm_first like :s or pm_last like :s";
    }
    
    $stmt = $pdo->prepare($sql);
    if ($search) $stmt->bindValue(':s', "%$search%");
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_project_by_id($id) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

function create_project($name, $description, $pm_id) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("INSERT INTO projects (name, description, pm_id) VALUES (:name, :desc, :pm_id)");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':desc', $description);
    $stmt->bindValue(':pm_id', $pm_id);
    $stmt->execute();
}

function update_project($id, $name, $description, $pm_id) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("UPDATE projects SET name = :name, description = :desc, pm_id = :pm_id WHERE project_id = :id");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':desc', $description);
    $stmt->bindValue(':pm_id', $pm_id);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

    //---------partya dyal PM dashboard----------
    function get_pm_projects($pm_id) {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE pm_id = :pm_id");
        $stmt->bindValue(':pm_id', $pm_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function get_devs_by_project($project_id){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT u.* FROM users u
        JOIN Project_Assignments pd ON u.user_id = pd.user_id
        WHERE pd.project_id = :project_id");
        $stmt->bindValue(':project_id', $project_id);
        $stmt->execute();
        return $stmt->fetchAll();
    
    }
    function get_pm_tasks($pm_id){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT t.*, p.name as project_name FROM tasks t
        JOIN projects p ON t.project_id = p.project_id
        WHERE p.pm_id = :pm_id");
        $stmt->bindValue(':pm_id', $pm_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function get_available_devs_for_project($project_id) {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM users 
                               WHERE role = 'Developer' 
                               AND user_id NOT IN (
                                   SELECT user_id FROM Project_Assignments WHERE project_id = :project_id
                               )");
        $stmt->bindValue(':project_id', $project_id);
        $stmt->execute();
        return $stmt->fetchAll();    
    }

    function assign_user_to_project($user_id, $project_id) {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("INSERT INTO Project_Assignments (project_id, user_id) VALUES (:project_id, :user_id)");
        $stmt->bindValue(':project_id', $project_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $user_name = get_user_by_id($user_id)['username'];
        $project_name = get_project_by_id($project_id)['name'];
        $action = "Assigned developer '$user_name' to project '$project_name'";
        post_log($pdo, $action, $project_name);
    }

    function remove_user_from_project($user_id, $project_id){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("DELETE FROM Project_Assignments WHERE project_id = :project_id AND user_id = :user_id");
        $stmt->bindValue(':project_id', $project_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $user_name = get_user_by_id($user_id)['username'];
        $project_name = get_project_by_id($project_id)['name'];
        $action = "Removed developer '$user_name' from project '$project_name'";
        post_log($pdo, $action, $project_name);
    }

    function get_dev_by_id($user_id){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id AND role = 'Developer'");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function create_task($task_name, $description ,$project_id, $user_id, $deadline){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("INSERT INTO tasks (title, project_id, description, assigned_to, deadline) 
        VALUES (:title, :project_id, :description, :user_id, :deadline)");
        $stmt->bindValue(':title', $task_name);
        $stmt->bindValue(':project_id', $project_id);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':deadline', strtotime($deadline));
        $stmt->execute();

        $user_name = get_user_by_id($user_id)['username'];
        $project_name = get_project_by_id($project_id)['name'];
        $action = "Created task '$task_name' for developer '$user_name' in project '$project_name'";
        post_log($pdo, $action, $project_name);
    }
    function get_task_by_id($task_id){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE task_id = :task_id");
        $stmt->bindValue(':task_id', $task_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function delete_task($task_id){
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE task_id = :task_id");
        $stmt->bindValue(':task_id', $task_id);
        $stmt->execute();
        $task_name = get_task_by_id($task_id)['title'];
        $action = "Deleted task '$task_name' with ID '$task_id'";
    }
    function format_time_ago($datetime) {
        $now = new DateTime();
        $ago = new DateTime("@$datetime");
        $diff = $now->diff($ago);

        if ($diff->d > 0 || $diff->m > 0 || $diff->y > 0) {
            return $ago->format('j-M-Y'); 
        }
        if ($diff->h > 0) {
            return $diff->h . 'h ago';
        }

        if ($diff->i > 0) {
            return $diff->i . 'min ago';
        }
        return 'Just now';
    }
?>