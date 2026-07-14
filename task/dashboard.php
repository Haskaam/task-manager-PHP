<!DOCTYPE html> 
<?php 
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$editingTaskId = null;

if(isset($_POST['edit'])) {
    $editingTaskId = $_POST['task_id'];
}

if(isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Dashboard</title>
        <link rel="stylesheet" href="dashboard.css">
    </head>
    <body>
        <header>
            <h1>TaskManager</h1>

            <span class="nav">Witaj, 
                <?php 
                echo($_SESSION['username']);
                ?>
            </span>
        </header>

    <div class="content">
        <aside>
            <h3>Panel boczny</h3>
            <a href="logout.php" class="logout">Wyloguj się</a>
        </aside>

        <main>
            <div class="add_task">
                <form method="POST">
                    <p>Dodaj zadanie</p>
                    <input type="text" id="nazwa_input" name="nazwa_zadania" placeholder="Nazwa zadania ..."><br>
                    <textarea name="opis_zadania" placeholder="Opis zadania ..."></textarea><br>
                    <button type=submit name="dodaj">Dodaj zadanie</button><br>
                    <p></p>

                    <?php 
                    $host = "localhost";
                    $dbname = "taskmanager";
                    try {
                    $connect = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", "root", "");

                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch(PDOException $e) {
                        die("Connection failed: " . $e->getMessage());
                    }

                    if(isset($_POST['dodaj'])) {
                        $opis = trim($_POST['opis_zadania']);
                        $nazwa = trim($_POST['nazwa_zadania']);
                        $userid = $_SESSION['user_id'];

                        if(empty($nazwa)) {
                            echo("Podaj nazwę zadania.");
                            exit;
                        }

                        $stmt = $connect->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");

                        $stmt->execute([$userid, $nazwa, $opis]);

                        header("Location: dashboard.php");
                        exit;
                    }
                    ?>
                </form>
            </div>
                <?php 
                $stmt = $connect->prepare("SELECT * FROM tasks WHERE user_id = ?");

                $stmt->execute([$_SESSION['user_id']]);

                $tasks = $stmt->fetchAll();

                
                    if(isset($_POST['usun'])) {
                        $taskid = $_POST['task_id'];

                        $usuwanie = $connect->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");

                        $usuwanie->execute([$taskid, $_SESSION['user_id']]);

                        header("Location: dashboard.php");
                        exit;
                    }

                    if(isset($_POST['done'])) {
                        $taskId = $_POST['task_id'];

                        $zakoncz = $connect->prepare("UPDATE tasks SET status = 'done' WHERE id = ? AND user_id = ?");

                        $zakoncz->execute([$taskId, $_SESSION['user_id']]);
                        
                        header("Location: dashboard.php");
                        exit;
                    }
                    

            foreach($tasks as $task): ?>

                <section class="task <?= $task['status'] ?>">
                    <?php if($editingTaskId == $task['id']): ?>

                    <form method="POST">

                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

                    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>">

                    <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea>

                    <button type="submit" name="save">Zapisz</button>
                    </form>

                    <?php else: ?>


                    <h3><?=  htmlspecialchars($task['title']) ?></h3>

                    <p class="status">Status: <?= htmlspecialchars($task['status']) ?></p>

                    <p><?= htmlspecialchars($task['description']) ?></p>

                    <form method="POST">
                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

                        <button class="donebtn" type="submit" name="done" >Zakończ</button>

                        <button class="edit" type="submit" name="edit">Edytuj</button>
                    <button
                     class="delete"
                     type="submit"
                      onclick=" return confirm('Czy na pewno usunąć zadanie <?= htmlspecialchars($task['title']) ?>?')"
                      name="usun"
                      >
                      Usuń
                    </button>

                    
                    </form>

                    <?php endif; ?>

                </section>

            <?php endforeach; ?>

            <?php 
            if(isset($_POST['save'])) {
                $taskId = $_POST['task_id'];
                $title = trim($_POST['title']);
                $description = trim($_POST['description']);

                $stmt = $connect->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");

                $stmt->execute([$title, $description, $taskId, $_SESSION['user_id']]);

                header("Location: dashboard.php");
                exit;
            }
            ?>
        </main>
    </div>

        <footer>
            2026 TaskManager. Wszystkie prawa zastrzeżone. 
        </footer>
    </body>
</html>