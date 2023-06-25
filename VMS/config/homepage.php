<?php
require('../config/db.php');
require('../config/menu.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : "";

$slidesQuery = "SELECT * FROM homepage_slides";
$slidesResult = mysqli_query($conn, $slidesQuery);

$slideshows = array();
while($slidesRow = mysqli_fetch_assoc($slidesResult)) {
    $slideshows[] = $slidesRow;
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
   
    <div class="view-homepage">    
        <div class="slideshow-container">
            <?php foreach($slideshows as $slide) { ?>
                <div class="slide fade">
                    <img src="<?php echo $slide['slide_image']; ?>" alt="slide_image" class="slide-fade">  
                    <div class="button-container">
                        <h2 class="caption-slide"> <?php echo $slide['slide_caption']; ?></h2>
                        <a href="../user/view_events.php?event_id=<?php echo $slide['event_id']; ?>" class="button">View More → </a>
                    </div>
                </div>
            <?php } ?>
            
          
        </div>
    </div>

     
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

                                    <a href="../user/view_events.php?event_id=<?php echo $row['event_id']; ?>" class="join ">Event Ended! <i class='bx bx-right-arrow-alt'></i></a>
                                                
                                    
                                    <div class="author"> By <span class="name"><?php echo $username; ?></span> 4 days ago</div>
                                </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>
    
   

    <div class="content"></div>

    <script src="../js/dropdown-menu.js"></script>
    <script src="../js/slideshow.js"></script>
    <script src="../js/menu-icon.js"></script>
    
</body>
</html>