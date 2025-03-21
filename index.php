<?php
    include 'db.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
        $task = filter_input(INPUT_POST, "task", FILTER_SANITIZE_SPECIAL_CHARS);

        if (!empty($task)) {
            $stmt = $conn->prepare("INSERT INTO `td_table` (`task`) VALUES (?)");
            $stmt->bind_param("s", $task);

            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
            else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    if (isset($_GET['complete'])) {
        $task_id = (int)$_GET['complete'];
        $stmt = $conn->prepare("UPDATE `td_table` SET `completed` = 1 WHERE `id` = ?");
        $stmt->bind_param("i", $task_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_GET['rcomplete'])) {
        $task_id = (int)$_GET['rcomplete'];
        $stmt = $conn->prepare("UPDATE `td_table` SET `completed` = 0 WHERE `id` = ?");
        $stmt->bind_param("i", $task_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_GET['delete'])) {
        $task_id = (int)$_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM `td_table` WHERE `id` = ?");
        $stmt->bind_param("i", $task_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $incompleteSql = "SELECT * FROM td_table WHERE completed = 0";
    $incompleteResult = mysqli_query($conn, $incompleteSql);

    $completedSql = "SELECT * FROM td_table WHERE completed = 1";
    $completedResult = mysqli_query($conn, $completedSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do List</title>
    <link rel = "stylesheet" href = "style.css">
</head>

<body>
    <aside>
        <div class="header"><b>
            To-do List
        </b></div>
        <div class="addt">
            <form action="">
                <input class="nl" type="text" id="input task" placeholder="Add List">
                <button type="submit" class="lbutton" style="color: white;"><b>+</b></button>
            </form>
        </div>
        <div class="ll"></div>
        <div class="tlist"><span><b>My Lists</b></span>
            <div class="lbox">

            </div>
        </div>
    </aside>

    <div class="task">
        <div class="h"><b>New Task</b></div>
        <div class="newt">
            <form method = "post">
                <input name = "task" class="nt" type="text" id="ntask" placeholder="Add Task">
                <button type = submit class="tbutton" style="color: white;">+</button>
            </form>
        </div>

        <div class="Tl">
            <div class="tl"></div>
            <div class="hl"><span><b>Task Lists</b></span></div>
            <div class="tbox">
            <?php
                if ($incompleteResult && mysqli_num_rows($incompleteResult) > 0) {
                    while ($row = mysqli_fetch_assoc($incompleteResult)) {
                        $taskId = $row['id'];
                        $task = htmlspecialchars($row['task']);

                        echo "<div class='titem'>";
                        echo "<a href='?complete=$taskId' class='cbutton'></a>";
                        echo "<span class='ttext'>$task</span>";
                        echo "<a href='?delete=$taskId' class='dtbutton'><b>✖</b></a>";
                        echo "</div>";
                    }
                } 
            ?>
            </div>
        </div>
        

        <div>
            <div class="hl"><span><b>Completed Task</b></span></div>
            <div class="cbox"> 
            <?php
                if ($completedResult && mysqli_num_rows($completedResult) > 0) {
                    while ($row = mysqli_fetch_assoc($completedResult)) {
                        $taskId = $row['id'];
                        $task = htmlspecialchars($row['task']);

                        echo "<div class='citem'>";
                        echo "<a href='?rcomplete=$taskId' class='rcbutton'>";
                        echo "<span class='icircle'></span>";
                        echo "</a>";
                        echo "<span class='cttext' style = 'text-decoration: line-through'>$task</span>";
                        echo "<a href='?delete=$taskId' class='dtbutton'><b>✖</b></a>";
                        echo "</div>";
                    }
                }
            ?>
            </div>
        </div>
    </div>
</body>
</html>