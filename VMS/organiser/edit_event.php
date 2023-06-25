<?php
require('../config/db.php');
require('../config/menu.php');
require('../organiser/organiser_dashboard.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

$userID = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventID = $_POST['id'];
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $endTime = $_POST['endTime'];
    $eventDescription = $_POST['eventDescription'];
    $eventLocation = $_POST['eventLocation'];
    $startTime = date("H:i", strtotime($_POST['startTime']));
    $endTime = date("H:i", strtotime($_POST['endTime']));

    $eventPicture = '';
    if (isset($_FILES['eventPicture']) && $_FILES['eventPicture']['error'] === 0) {
        $pictureTmpName = $_FILES['eventPicture']['tmp_name'];
        $pictureName = $_FILES['eventPicture']['name'];
        $picturePath = '../img/' . $pictureName;

        if (move_uploaded_file($pictureTmpName, $picturePath)) {
            $eventPicture = $picturePath;
        }  
    } else {
        $query = "SELECT eventPicture FROM events WHERE event_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $eventID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows === 0){
            header("Location: view)event.php");
            exit();
        }
        $eventData = $result->fetch_assoc();
        $eventPicture = $eventData['eventPicture'];
    }

    $query = "UPDATE events SET eventName = ?, eventDate = ?, eventDescription = ?, eventPicture = ?, eventLocation = ?,  startTime = ?,  endTime = ?, approvalStatus = 'Pending' WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $eventName, $eventDate, $eventDescription, $eventPicture, $eventLocation, $startTime, $endTime, $eventID);
    $stmt->execute();
    header("Location: view_event.php");
    exit();
}

if (isset($_GET['id'])) {
    $eventID = $_GET['id'];
    $query = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: view_event.php");
        exit();
    }
    $event = $result->fetch_assoc();
    } else {
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

    <form class="edit-event" action="edit_event.php" method="post" enctype="multipart/form-data">
        <h1 class="create-event">Edit Event</h1>
        <input type="hidden" name="id" value="<?php echo $event['event_id']; ?>">
        <table>
            <tr>
                <td><label for="eventName">Event Name:</label></td>
                <td><input type="text" name="eventName" id="eventName" value="<?php echo $event['eventName']; ?>" required></td>
            </tr>
            <tr>
                <td><label for="eventDate">Event Date:</label></td>
                <td><input type="date" name="eventDate" id="eventDate" value="<?php echo $event['eventDate']; ?>" required></td>
            </tr>
            <tr>
                <td><label for="startTime">Start Time:</label></td>
                <td><input type="time" name="startTime" id="startTime" value="<?php echo isset($event['startTime']) ? date("H:i", strtotime($event['startTime'])) : ''; ?>" required></td>
            </tr>
            <tr>
                <td><label for="endTime">End Time:</label></td>
                <td><input type="time" name="endTime" id="endTime" value="<?php echo isset($event['endTime']) ? date("H:i", strtotime($event['endTime'])) : ''; ?>" required></td>
            </tr>
            <tr>
                <td><label for="eventDescription">Event Description:</label></td>
                <td><textarea name="eventDescription" id="eventDescription" required><?php echo $event['eventDescription']; ?></textarea></td>
            </tr>
            <tr>
                <td><label for="eventLocation">Event Location:</label></td>
                <td><textarea name="eventLocation" id="eventLocation" required><?php echo $event['eventLocation']; ?></textarea></td>
            </tr>
            <tr>
                <td><label for="currentPicture">Event Picture:</label></td>
                <td>
                    <?php if (!empty($event['eventPicture'])): ?>
                        <img src="<?php echo $event['eventPicture']; ?>" alt="Event Picture" width="200px">
                    <?php endif; ?>
                </td>
                <td><input type="file" name="eventPicture" id="eventPicture" accept="image/*"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Update Event</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='view_event.php';">Cancel</button>
                </td>
            </tr>
        </table>
    </form>
    
            

    <script src="../js/dropdown-menu.js"></script>



     
   
</body>
</html>