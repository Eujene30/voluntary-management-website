<?php
require('../config/db.php');
require('../config/menu.php');
require('../organiser/organiser_dashboard.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $startTime = date("g:iA", strtotime($_POST['startTime']));
    $endTime = date("g:iA", strtotime($_POST['endTime']));
    $eventDescription = $_POST['eventDescription'];
    $eventLocation = $_POST['eventLocation'];

    $eventPicture = '';
    if (isset($_FILES['eventPicture']) && $_FILES['eventPicture']['error'] === 0) {
        $pictureTmpName = $_FILES['eventPicture']['tmp_name'];
        $pictureName = $_FILES['eventPicture']['name'];
        $picturePath = '../img/' . $pictureName;

        if (move_uploaded_file($pictureTmpName, $picturePath)) {
            $eventPicture = $picturePath;
        }
    }
    $query = "INSERT INTO events (eventName, eventDate, eventDescription, eventPicture, eventLocation, organiserID, startTime, endTime, approvalStatus ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssiss", $eventName, $eventDate, $eventDescription, $eventPicture, $eventLocation, $userID, $startTime, $endTime);
    $stmt->execute();

    $eventID = $stmt->insert_id;

    $query = "SELECT organiserID FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $organiserID = $event['organiserID'];
    
    $message = "A new event submitted";
    $timestamp= date('Y-m-d H:i:s');
    $read_status = 0;
    $role = 'organiser';

    $query = "INSERT INTO notifications (user_id, event_id, organiserID, message, created_at, read_status, role)
            VALUES (?,?,?,?,?,?,?)" ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissis", $user_id , $eventID, $organiserID, $message, $timestamp, $read_status, $role);
    $stmt->execute();


    header("Location: view_event.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voluntree</title> 
    <link rel="stylesheet" href="../css/create_event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
   </head>
<body>
    
    <form class="event-form" action="create_event.php" method="post" enctype="multipart/form-data">
        <h1>Create Event</h1>
        <table>
            <tr>
                <td><label for="eventName">Event Name:</label></td>
                <td><input type="text" name="eventName" id="eventName" required></td>
            </tr>
            <tr>
                <td><label for="eventDate">Event Date:</label></td>
                <td><input type="date" name="eventDate" id="eventDate" required></td>
            </tr>
            <tr>
                <td><label for="startTime">Start Time:</label></td>
                <td><input type="time" name="startTime" id="startTime" value="<?= date('H:i') ?>" required></td>
            </tr>
            <tr>
                <td><label for="endTime">End Time:</label></td>
                <td><input type="time" name="endTime" id="endTime" value="<?= date('H:i') ?>"  required></td>
            </tr>
            <tr>
                <td><label for="eventDescription">Event Description:</label></td>
                <td><textarea name="eventDescription" id="eventDescription" required></textarea></td>
            </tr>
            <tr>
                <td><label for="eventLocation">Event Location:</label></td>
                <td><textarea name="eventLocation" id="eventLocation" required></textarea></td>
            </tr>
            <tr>
                <td><label for="eventPicture">Event Picture:</label></td>
                <td><input type="file" name="eventPicture" id="eventPicture" accept="image/*" required></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit">Submit Event</button></td>
            </tr>
        </table>
    </form>
   
</body>
</html>