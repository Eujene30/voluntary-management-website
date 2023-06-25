<?php
require('../config/db.php');
include('../config/menu.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}


$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : "";

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
    <div class="view-event">  
        <h1> EVENTS</h1>
        <div class="card-container">
            <?php
                $query = "SELECT * FROM events WHERE approvalStatus = 'Approved'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    $userId = $row['organiserID'];
                    $usernameQuery = "SELECT username FROM users WHERE user_id = '$userId'";
                    $usernameResult = mysqli_query($conn, $usernameQuery);
                    $usernameRow = mysqli_fetch_assoc($usernameResult);
                    $username = $usernameRow['username'];

                    $eventID = $row['event_id'];
                    ?>
                        <div class="card">
                            <div class="card-image">
                                <img src="../img/<?php echo $row['eventPicture']; ?>" alt="Event Image" class="event-image">
                            </div>
                            <div class="category">
                                <h3><?php echo $row['eventName']; ?></h3>
                            </div>
                            <div class="heading">
                                    <p>Date: <?php echo $row['eventDate']; ?></p>
                                    <td><?php echo date("g:iA", strtotime($row['startTime'])); ?> to <?php echo date("g:iA", strtotime($row['endTime'])); ?> </td>
                                    <p></p>
                                    <a href="view_events.php?event_id=<?php echo $row['event_id']; ?>" class="join ">Join Now! <i class='bx bx-right-arrow-alt'></i></a>
                                    <div class="author"> By <span class="name"><?php echo $username; ?></span> 4 days ago</div>
                                </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>

    <div class="view-event">  
        <h1> PASSED EVENTS</h1>
        <div class="card-container">
            <?php
                $query = "SELECT * FROM events WHERE approvalStatus = 'Complete'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    $userId = $row['organiserID'];
                    $usernameQuery = "SELECT username FROM users WHERE user_id = '$userId'";
                    $usernameResult = mysqli_query($conn, $usernameQuery);
                    $usernameRow = mysqli_fetch_assoc($usernameResult);
                    $username = $usernameRow['username'];

                    $eventID = $row['event_id'];
                    ?>
                        <div class="card">
                            <div class="card-image">
                                <img src="../img/<?php echo $row['eventPicture']; ?>" alt="Event Image" class="event-image">
                            </div>
                            <div class="category">
                                <h3><?php echo $row['eventName']; ?></h3>
                            </div>
                            <div class="heading">
                                    <p>Date: <?php echo $row['eventDate']; ?></p>
                                    <td><?php echo date("g:iA", strtotime($row['startTime'])); ?> to <?php echo date("g:iA", strtotime($row['endTime'])); ?> </td>
                                    <p></p>
                                    <a href="view_events.php?event_id=<?php echo $row['event_id']; ?>" class="join ">Event Ended! <i class='bx bx-right-arrow-alt'></i></a>
                                    <div class="author"> By <span class="name"><?php echo $username; ?></span> 4 days ago</div>
                                </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>

    <div class="content"></div>

 
    
</body>
</html>