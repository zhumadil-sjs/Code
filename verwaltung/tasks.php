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

// Aufgaben anzeigen
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['action'])) {
  checkLogin();

  $project_id = $_GET['project_id'];

  // Projekt abrufen
  $query = "SELECT * FROM projects WHERE id = $project_id";
  $result = mysqli_query($conn, $query);
  $project = mysqli_fetch_assoc($result);

  // Aufgaben abrufen
  $query = "SELECT * FROM tasks WHERE project_id = $project_id";
  $result = mysqli_query($conn, $query);
  $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
  <title><?php echo $project['name']; ?></title>
  <style>
  body {
    font-family: Arial, sans-serif;
  }

  h1,
  h2 {
    margin-bottom: 10px;
  }

  ul {
    list-style-type: none;
    padding-left: 0;
  }

  li {
    margin-bottom: 5px;
  }

  a {
    text-decoration: none;
    color: blue;
    margin-left: 10px;
  }
  </style>
</head>

<body>
  <h1><?php echo $project['name']; ?></h1>

  <h2>Aufgaben</h2>
  <ul>
    <?php foreach ($tasks as $task) : ?>
    <li>
      <?php echo $task['name']; ?> - <?php echo $task['status']; ?>
      <a href="/edit_task.php?php echo $task['id']; ?>">Bearbeiten</a>
      <a href="/delete_task.php?php echo $task['id']; ?>">Löschen</a>
    </li>
    <?php endforeach; ?>
  </ul>

  <a href="/add_task.php?project_id=<?php echo $project_id; ?>">Aufgabe hinzufügen</a>
  <br>
  <a href="/index.php">Zurück zur Projektliste</a>

</body>

</html>

<?php
  exit();
}
?>