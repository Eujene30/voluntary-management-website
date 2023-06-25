<?php
require('../config/db.php');
include('../config/menu.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT vh.user_id, vh.name, e.event_id, e.eventName, e.eventDate, e.startTime, e.endTime, e.eventDescription, e.eventLocation FROM events e
                        INNER JOIN volunteer_history vh ON e.event_id = vh.event_id 
                        WHERE vh.status = "Complete" AND vh.user_id = ?
                        ORDER BY e.eventDate DESC');
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
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
    <div class="event-container">  
        <h1> COMPLETED EVENTS</h1>
        <?php if ($result->num_rows>0) { ?>
            <div class="card-lists">
                <?php while($row=$result->fetch_assoc()){
                    ?>
                        <div class="card-events">
                            <h2><?php echo $row['eventName'] ?></h2>
                            <p>Volunteer Name: <?php echo $row['name']; ?></p>
                            <p>Date: <?php echo $row['eventDate']; ?></p>
                            <p>Time: <?php echo $row['startTime']. 'to' .$row['endTime']; ?></p>
                            <p>Description: <?php echo $row['eventDescription']; ?></p>
                            <p>Location: <?php echo $row['eventLocation']; ?></p>
                            <a href="feedbackPage.php?event_id=<?php echo $row['event_id']; ?>" class="">Give Feedback</a>
                        </div>
                    <?php
                } ?>
            </div>
        
        <?php } else { ?>
            <p>You haven't completed any event.</p>
        <?php } ?>

       
    </div>

    <div class="content"></div>

 
    
</body>
</html>

<?php
$stmt->close();
$result->close();

?>