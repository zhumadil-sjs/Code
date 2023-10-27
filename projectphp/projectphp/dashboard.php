<?php
session_start();

// Überprüfe, ob der Benutzer angemeldet ist, um Zugriff auf das Dashboard zu gewähren
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Verbindung zur Datenbank herstellen
$conn = new mysqli("localhost", "root", "", "projektverwaltung");

if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Erfolgsmeldung überprüfen
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Fehlermeldung überprüfen
if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Projekte anzeigen
$projects = array();
$sql = "SELECT * FROM projects";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

// Mitglieder anzeigen
$members = array();
$sql = "SELECT * FROM members";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}

// Projekt hinzufügen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addProject"])) {
    $projectName = $_POST["projectName"];

    // SQL-Abfrage, um das Projekt in die Datenbank einzufügen
    $sql = "INSERT INTO projects (name) VALUES ('$projectName')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Projekt erfolgreich hinzugefügt";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Fehler beim Hinzufügen des Projekts: " . $conn->error;
    }
}

// Projekt bearbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editProject"])) {
    $projectId = $_POST["projectId"];
    $projectName = $_POST["projectName"];

    // SQL-Abfrage, um das Projekt in der Datenbank zu aktualisieren
    $sql = "UPDATE projects SET name='$projectName' WHERE id='$projectId'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Projekt erfolgreich aktualisiert";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Fehler beim Aktualisieren des Projekts: " . $conn->error;
    }
}

// Projekt löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteProject"])) {
    $projectId = $_POST["projectId"];

    // SQL-Abfrage, um das Projekt aus der Datenbank zu löschen
    $sql = "DELETE FROM projects WHERE id='$projectId'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Projekt erfolgreich gelöscht";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Fehler beim Löschen des Projekts: " . $conn->error;
    }
}

// Mitglied hinzufügen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addMember"])) {
    $memberName = $_POST["memberName"];

    // SQL-Abfrage, um das Mitglied in die Datenbank einzufügen
    $sql = "INSERT INTO members (name) VALUES ('$memberName')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Mitglied erfolgreich hinzugefügt";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Fehler beim Hinzufügen des Mitglieds: " . $conn->error;
    }
}

// Mitglied bearbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editMember"])) {
    $memberId = $_POST["memberId"];
    $memberName = $_POST["memberName"];

    // SQL-Abfrage, um das Mitglied in der Datenbank zu aktualisieren
    $sql = "UPDATE members SET name='$memberName' WHERE id='$memberId'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Mitglied erfolgreich aktualisiert";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Fehler beim Aktualisieren des Mitglieds: " . $conn->error;
    }
}

// Mitglied löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteMember"])) {
    $memberId = $_POST["memberId"];

    // SQL-Abfrage, um das Mitglied aus der Datenbank zu löschen
    $sql = "DELETE FROM members WHERE id='$memberId'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Mitglied erfolgreich gelöscht";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Fehler beim Löschen des Mitglieds: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Projektverwaltung - Dashboard</title>
  <link rel="stylesheet" type="text/css" href="/style.css">
</head>

<body>
  <div class="container">
    <h2>Projektverwaltung - Dashboard</h2>
    <p>Eingeloggt als: <?php echo $_SESSION['username']; ?></p>

    <?php if (isset($successMessage)) { ?>
    <p class="success-message"><?php echo $successMessage; ?></p>
    <?php } ?>

    <?php if (isset($errorMessage)) { ?>
    <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php } ?>

    <h3>Projekte:</h3>
    <ul>
      <?php foreach ($projects as $project) { ?>
      <li><?php echo $project['name']; ?>
        <form action="dashboard.php" method="POST" class="form-inline">
          <input type="hidden" name="projectId" value="<?php echo $project['id']; ?>">
          <input type="text" name="projectName" placeholder="Projektname" required>
          <button type="submit" name="editProject">Bearbeiten</button>
          <button type="submit" name="deleteProject">Löschen</button>
        </form>
      </li>
      <?php } ?>
    </ul>

    <h3>Mitglieder:</h3>
    <ul>
      <?php foreach ($members as $member) { ?>
      <li><?php echo $member['name']; ?>
        <form action="dashboard.php" method="POST" class="form-inline">
          <input type="hidden" name="memberId" value="<?php echo $member['id']; ?>">
          <input type="text" name="memberName" placeholder="Mitgliedsname" required>
          <button type="submit" name="editMember">Bearbeiten</button>
          <button type="submit" name="deleteMember">Löschen</button>
        </form>
      </li>
      <?php } ?>
    </ul>

    <h3>Projekt hinzufügen:</h3>
    <form action="dashboard.php" method="POST">
      <input type="text" name="projectName" placeholder="Projektname" required>
      <button type="submit" name="addProject">Projekt hinzufügen</button>
    </form>

    <h3>Mitglied hinzufügen:</h3>
    <form action="dashboard.php" method="POST">
      <input type="text" name="memberName" placeholder="Mitgliedsname" required>
      <button type="submit" name="addMember">Mitglied hinzufügen</button>
    </form>

    <br>
    <a href="logout.php">Abmelden</a>
  </div>
</body>

</html>