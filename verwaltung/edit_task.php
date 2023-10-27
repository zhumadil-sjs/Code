<?php
session_start();
// MySQL-Datenbankverbindung herstellen
$host = 'localhost';
$db = 'project_management';
$user = 'root';
$password = '';

// Verbindung herstellen
$conn = mysqli_connect('$host', '$user', '$password', '$db');

// Überprüfen des Benutzerlogins
function checkLogin()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
  }
}

// Bearbeiten einer Aufgabe
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'edit_task' && isset($_GET['task_id'])) {
  checkLogin();

  $task_id = $_GET['task_id'];

  $query = "SELECT * FROM tasks WHERE id = $task_id";
  $result = mysqli_query($conn, $query);
  $task = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>

<head>
  <title>Aufgabe bearbeiten</title>
  <style>
  body {
    font-family: Arial, sans-serif;
  }

  h1 {
    margin-bottom: 10px;
  }

  form {
    margin-bottom: 10px;
  }

  label {
    display: block;
    margin-bottom: 5px;
  }

  input[type="text"],
  textarea {
    margin-bottom: 10px;
  }
  </style>
</head>

<body>
  <h1>Aufgabe bearbeiten</h1>

  <form action="/edit_task.php" method="POST">
    <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">

    <label for="task_name">Name:</label>
    <input type="text" id="task_name" name="task_name" value="<?php echo $task['name']; ?>" required>

    <label for="task_description">Beschreibung:</label>
    <textarea id="task_description" name="task_description" required><?php echo $task['description']; ?></textarea>

    <label for="task_status">Status:</label>
    <select id="task_status" name="task_status" required>
      <option value="Pending" <?php if ($task['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
      <option value="In Progress" <?php if ($task['status'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
      <option value="Completed" <?php if ($task['status'] === 'Completed') echo 'selected'; ?>>Completed</option>
    </select>

    <button type="submit">Aufgabe aktualisieren</button>
  </form>

  <a href="/tasks.php?php echo $task['project_id']; ?>">Zurück zur Aufgabenliste</a>

</body>

</html>

<?php
  exit();
}

// Aufgabe bearbeiten - Verarbeitung der Formulardaten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id']) && isset($_POST['task_name']) && isset($_POST['task_description']) && isset($_POST['task_status'])) {
  checkLogin();

  $task_id = $_POST['task_id'];
  $task_name = $_POST['task_name'];
  $task_description = $_POST['task_description'];
  $task_status = $_POST['task_status'];

  $query = "UPDATE tasks SET name = '$task_name', description = '$task_description', status = '$task_status' WHERE id = $task_id";
  mysqli_query($conn, $query);

  $query = "SELECT project_id FROM tasks WHERE id = $task_id";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $project_id = $row['project_id'];

  header("Location: tasks.php?project_id=$project_id");
  exit();
}
?>