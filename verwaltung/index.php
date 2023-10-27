<?php
session_start();
// MySQL-Datenbankverbindung herstellen
$host = '127.0.0.1';
$db = 'project_management';
$user = 'root';
$password = '';

// Verbindung herstellen
$conn = mysqli_connect('127.0.0.1', 'root', '', 'project_management');
// Überprüfen, ob die Verbindung erfolgreich war
if ($connection == false) {
echo ('Verbindung fehlgeschlagen: ' . mysqli_connect_error());
exit(); // Codes werden nicht mehr ausgeführt.

// Überprüfen des Benutzerlogins
function checkLogin()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
  }
}

// Startseite
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['action'])) {
  checkLogin();

  // Projekte abrufen
  $query = "SELECT * FROM projects";
  $result = mysqli_query($conn, $query);
  $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

  // Team-Mitglieder abrufen
  $query = "SELECT * FROM team_members";
  $result = mysqli_query($conn, $query);
  $teamMembers = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
  <title>Projektverwaltung</title>
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
  }
  </style>
</head>

<body>
  <h1>Projektverwaltung</h1>

  <h2>Projekte</h2>
  <ul>
    <?php foreach ($projects as $project) : ?>
    <li>
      <a href="tasks.php?project_id=<?php echo $project['id']; ?>">
        <?php echo $project['name']; ?>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>

  <h2>Team-Mitglieder</h2>
  <ul>
    <?php foreach ($teamMembers as $member) : ?>
    <li><?php echo $member['name']; ?> - <?php echo $member['email']; ?></li>
    <?php endforeach; ?>
  </ul>

  <a href="logout.php">Logout</a>

</body>

</html>

<?php
  exit();
}

// Login-Seite
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'login') {
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
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
  input[type="password"] {
    margin-bottom: 10px;
  }
  </style>
</head>

<body>
  <h1>Login</h1>

  <form action="login.php" method="POST">
    <label for="username">Benutzername:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Passwort:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
  </form>

</body>

</html>

<?php
  exit();
}

// Überprüfen des Benutzerlogins
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['username'] === 'admin' && $_POST['password'] === 'password') {
  $_SESSION['user_id'] = 1;
  header('Location: index.php');
  exit();
} else {
  header('Location: login.php');
  exit();
}
?>