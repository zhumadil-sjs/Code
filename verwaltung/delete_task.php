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

// Aufgabe löschen
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['task_id'])) {
    checkLogin();

    $task_id = $_GET['task_id'];

    $query = "SELECT project_id FROM tasks WHERE id = $task_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $project_id = $row['project_id'];

    $query = "DELETE FROM tasks WHERE id = $task_id";
    mysqli_query($conn, $query);

    header("Location: tasks.php?project_id=$project_id");
    exit();
}