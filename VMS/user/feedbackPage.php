<?php
require('../config/db.php');
include('../config/menu.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}
if (isset($_SESSION['user_id'])) {
   $user_id=$_SESSION['user_id'];
}



if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $feedback_comments = $_POST['feedback_comments'];
    
    
    $stmt = $conn->prepare('INSERT INTO feedbacks (user_id, event_id, name, feedback_text) VALUES (?,?,?,?)');
    $stmt->bind_param("isss", $user_id, $event_id, $name, $feedback_comments);
    $stmt->execute();


    header("Location: feedbacks.php");
    exit();
}


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
            <?php         
                if(isset($_GET['event_id'])) {
                    $event_id = $_GET['event_id'];   

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
                        
                        
                        echo '<h1>' .$eventName. '</h1>';
                        echo '<img src="../img/' .$eventPicture. '" alt="Event Image" class="eventImage">';
                        echo '<hr class="lining">';
                        echo '<h3 class="fonts">Event Name - ' .$eventName. '</h3>';
                        echo '<hr class="lining-space">';
                        echo '<h3 class="fonts">Event Date - ' .$eventDate. '</h3>';
                        echo '<hr class="lining-space">';
                        echo '<h3 class="fonts">Event Description - ' .$eventDescription. '</h3>';
                        echo '<hr class="lining-space">';
                        echo '<h3 class="fonts">Event Location - ' .$eventLocation. '</h3>';
                        echo '<hr class="lining-space">';
                        echo '<h3 class="fonts">Event Time - ' .$startTime. ' to ' .$endTime. '</h3>';
                        echo '<hr class="lining-space">';
                        echo '<h3 class="fonts">Event Status - ' .$approvalStatus. '</h3>';
                        echo '<hr class="lining-space">';
                        echo $_GET['event_id'];
                        

                    }



                }

              
            ?>
        </div>

        <form action="feedbackPage.php" method="POST" class="register-form" enctype="multipart/form-data">
            <h1 class="feedback-title" >Give Feedback</h1>
            <input type="hidden" name="event_id" value="<?php echo $_GET['event_id']; ?>">
            <label for="name" >Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $username; ?>" class="feedback-input" required>

            <label for="feedback_comments">Feedback Comments:</label><br>
            <textarea id="feedback_comments" name="feedback_comments" rows="4" cols="50" class="feedback-input"></textarea>
            
            <input type="submit" name="volunteerFeedback" value="Submit Feedback">
        </form>
    </div>  
 
    
</body>
</html>

