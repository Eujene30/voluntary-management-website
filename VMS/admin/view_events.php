<?php
require('../config/db.php');
require('../config/admin_menu.php');



if(isset($_POST['rejectEvent'])){
    $eventID = $_POST['eventID'];
    $query = "UPDATE events SET approvalStatus = 'Rejected' WHERE event_id = $eventID";
    mysqli_query($conn, $query);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/event_approval.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
   <h1 class="view-events">Events Approved</h1>
   <table>
        <thead>
            <tr>
            <th>Event title</th>
            <th>Event organiser</th>
            <th>Event picture</th>
            <th>Event description</th>
            <th>Event location</th>
            <th>Event time</th>
            <th>Status</th>
            <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "SELECT * FROM events WHERE approvalStatus = 'Approved'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)){
                    $userId = $row['organiserID'];
                    $usernameQuery = "SELECT username FROM users WHERE user_id = '$userId'";
                    $usernameResult = mysqli_query($conn, $usernameQuery);
                    $usernameRow = mysqli_fetch_assoc($usernameResult);
                    $username = $usernameRow['username'];
                    ?> 
                        <tr>
                            <td><?php echo $row['eventName']; ?></td>
                            <td><?php echo $username; ?></td>
                            <td><img src="<?php echo $row['eventPicture']; ?>" alt="Event Picture" style="width: 100px;"></td>
                            <td><?php echo $row['eventDescription']; ?></td>
                            <td><?php echo $row['eventLocation']; ?></td>
                            <td><?php echo date("g:iA", strtotime($row['startTime'])); ?> to <?php echo date("g:iA", strtotime($row['endTime'])); ?> </td>
                            <td><?php echo $row['approvalStatus'];?></td>
                            <td>
                                <form action="event_approval.php" method="post" >
                                    <input type="hidden" name="eventID" value="<?php echo $row['event_id']; ?>">
                                    <button type="submit" name="rejectEvent" class="reject"> Reject </button>
                                </form>
                            </td>    
                        
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>

    <h1 class="view-events">View Events</h1>
   <table>
        <thead>
            <tr>
            <th>Event title</th>
            <th>Event organiser</th>
            <th>Event picture</th>
            <th>Event description</th>
            <th>Event location</th>
            <th>Event time</th>
            <th>Status</th>
            <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "SELECT * FROM events WHERE approvalStatus IN ('Complete', 'Rejected')";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)){
                    $userId = $row['organiserID'];
                    $usernameQuery = "SELECT username FROM users WHERE user_id = '$userId'";
                    $usernameResult = mysqli_query($conn, $usernameQuery);
                    $usernameRow = mysqli_fetch_assoc($usernameResult);
                    $username = $usernameRow['username'];
                    ?> 
                        <tr>
                            <td><?php echo $row['eventName']; ?></td>
                            <td><?php echo $username; ?></td>
                            <td><img src="<?php echo $row['eventPicture']; ?>" alt="Event Picture" style="width: 100px;"></td>
                            <td><?php echo $row['eventDescription']; ?></td>
                            <td><?php echo $row['eventLocation']; ?></td>
                            <td><?php echo date("g:iA", strtotime($row['startTime'])); ?> to <?php echo date("g:iA", strtotime($row['endTime'])); ?> </td>
                            <td><?php echo $row['approvalStatus'];?></td>
                            <td>
                                <form action="event_approval.php" method="post" >
                                    <input type="hidden" name="eventID" value="<?php echo $row['event_id']; ?>">
                                    <button type="submit" name="rejectEvent" class="reject"> Reject </button>
                                </form>
                            </td>    
                        
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
    
</body>
</html>