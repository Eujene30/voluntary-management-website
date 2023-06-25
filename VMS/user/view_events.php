<?php
require('../config/db.php');
include('../config/menu.php');



if (isset($_SESSION['user_id'])) {
   $user_id=$_SESSION['user_id'];
}

$event_id = $_GET['event_id'];   

$feedbackQuery = "SELECT * FROM feedbacks WHERE event_id = '$event_id'";
$resultFeedback = mysqli_query($conn ,$feedbackQuery);

$stmt = $conn->prepare('SELECT event_id, eventName, eventDate, eventDescription, eventLocation, organiserID, 
                        eventPicture, startTime,endTime, approvalStatus FROM events WHERE event_id =?');
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $eventName = $row['eventName'];
    $eventDate = $row['eventDate'];
    $eventDescription = $row['eventDescription'];
    $eventLocation = $row['eventLocation'];
    $eventPicture = $row['eventPicture'];
    $startTime = $row['startTime'];
    $endTime = $row['endTime'];
    $approvalStatus = $row['approvalStatus'];




?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voluntree</title> 
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
   </head>
<body>

    <div class="event-feedback">
        <div class="event-box">
            <h1><?php echo $eventName; ?></h1>;
            <div class="card-image">
                 <img src="../img/<?php echo $eventPicture; ?>"  class="photoImage">
            </div>
           
            <hr class="lining">
            <h3 class="fonts">Event Name - <?php echo $eventName; ?></h3>
            <hr class="lining-space">
            <h3 class="fonts">Event Date - <?php echo $eventDate; ?></h3>
            <hr class="lining-space">
            <h3 class="fonts">Event Description - <?php echo $eventDescription; ?></h3>
            <hr class="lining-space">
            <h3 class="fonts">Event Location - <?php echo $eventLocation; ?></h3>
            <hr class="lining-space">
            <h3 class="fonts">Event Time - <?php echo $startTime; ?> to <?php echo $endTime; ?></h3>
            <hr class="lining-space">
            <h3 class="fonts">Event Status - <?php echo $approvalStatus; ?></h3>
            <hr class="lining-space">

            <?php 
                $userStatus = "SELECT user_status FROM users WHERE user_id = ?";
                $userStatusStmt = $conn->prepare($userStatus);
                $userStatusStmt->bind_param("i", $_SESSION['user_id']);
                $userStatusStmt->execute();
                $userStatusResult = $userStatusStmt->get_result();
                $userStatusRow = $userStatusResult->fetch_assoc();
                if (isset($_SESSION['user_id'])) { 
                    if ($userStatus !== 'occupied') { 
                        $eventID = $row['event_id'];
                        $stmtEvent = $conn->prepare('SELECT approvalStatus FROM events WHERE event_id = ?');
                        $stmtEvent->bind_param('i', $eventID);
                        $stmtEvent->execute();
                        $resultEvent = $stmtEvent->get_result();
                        $event = $resultEvent->fetch_assoc();

                        if($event['approvalStatus'] === 'Approved') {
                            if($event['approvalStatus'] === 'Completed') { ?>
                                <p>Event Completed!</p>
                            <?php } else { ?>
                                <a href="register_form.php?id=<?php echo $row['event_id']; ?>" class="join ">Join Now! <i class='bx bx-right-arrow-alt'></i></a>
                            <?php }
                        }
                    } 
                } else { ?> 
                    <p><b>Please <a href="../config/login.php">Login</a> or <a href="../config/register.php">Register</a> to join event.</b></p>
            <?php } ?>
        </div>

        <h1>Feedbacks</h1>
        <div class="feedbackBox">
            <?php 
                while ($rowFeedback = mysqli_fetch_assoc($resultFeedback)) { ?>
                    <div>
                        <p class="name"><?php echo $rowFeedback['name']; ?></p>
                        <p class="feedback"><?php echo $rowFeedback['feedback_text']; ?></p>
                    </div>
                   
                <?php }
            ?>
        </div>
    </div>  

</body>
</html>
<?php
} else {
    header ("Location: events.php");
    exit();
}
?>

