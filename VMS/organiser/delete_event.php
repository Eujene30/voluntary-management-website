<?php
session_start();
require('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventID = $_POST['event_id'];

    $query = "DELETE FROM user_registration WHERE organiserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventID);
    $stmt->execute();

    $query = "DELETE FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventID);
    $stmt->execute();

    $query = "DELETE FROM notifications WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventID);
    $stmt->execute();



    header("Location: view_event.php");
    exit();
} else {
    header("Location: view_event.php");
    exit();
}
?>






