<?php
  session_start();
  require_once "db_connect.php";

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
  }

  $user_id = $_SESSION['user_id'];

  // Projekte anzeigen
  $sql = "SELECT * FROM projects WHERE user_id = $user_id";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "Projektname: " . $row['name'] . "<br>";
      echo "Beschreibung: " . $row['description'] . "<br>";
      echo "<a href='edit_project.php?id=" . $row['id'] . "'>Bearbeiten</a> | ";
      echo "<a href='delete_project.php?id=" . $row['id'] . "'>Löschen</a><br><br>";
    }
  } else {
    echo "Keine Projekte gefunden.";
  }

  // Projekt hinzufügen
  if (isset($_POST['add_project'])) {
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO projects (name, description, user_id) VALUES ('$project_name', '$description', $user_id)";
    $result = $conn->query($sql);

    if ($result) {
      echo "Projekt wurde hinzugefügt.";
    } else {
      echo "Fehler beim Hinzufügen des Projekts: " . $conn->error;
    }
  }
?>

<!DOCTYPE html>
<html>

<head>
  <title>Dashboard</title>
  <style>
  /* Beispiel-CSS-Stile */
  body {
    font-family: Arial, sans-serif;
  }

  h1,
  h2,
  h3 {
    color: #333;
  }

  form {
    margin-bottom: 20px;
  }

  input,
  textarea,
  button {
    margin-bottom: 10px;
  }

  button {
    padding: 10px 20px;
    background-color: #333;
    color: #fff;
    border: none;
    cursor: pointer;
  }

  button:hover {
    background-color: #555;
  }

  a {
    color: #333;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }
  </style>
</head>

<body>
  <h1>Dashboard</h1>

  <form method="POST" action="dashboard.php">
    <button type="submit" name="logout">Ausloggen</button>
  </form>

  <h2>Projekte</h2>
  <?php
    // Projekte anzeigen
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "Projektname: " . $row['name'] . "<br>";
        echo "Beschreibung: " . $row['description'] . "<br>";
        echo "<a href='edit_project.php?id=" . $row['id'] . "'>Bearbeiten</a> | ";
        echo "<a href='delete_project.php?id=" . $row['id'] . "'>Löschen</a><br><br>";
      }
    } else {
      echo "Keine Projekte gefunden.";
    }
  ?>

  <h3>Neues Projekt hinzufügen</h3>
  <form method="POST" action="dashboard.php">
    <input type="text" name="project_name" placeholder="Projektname" required><br>
    <textarea name="description" placeholder="Beschreibung"></textarea><br>
    <button type="submit" name="add_project">Projekt hinzufügen</button>
  </form>
</body>

</html>