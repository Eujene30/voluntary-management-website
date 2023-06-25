<?php
require('../config/db.php');
require('../organiser/organiser_dashboard.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

$userID = $_SESSION['user_id'];

$stmt = $conn->prepare('SELECT * FROM events WHERE organiserID = ?');
$stmt->bind_param('s', $userID);
$stmt->execute();
$result = $stmt->get_result();

?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voluntree</title> 
    <link rel="stylesheet" href="../css/view_event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
   </head>
<body>

    <div class="container">
        <div class="sidebar">
            <?php  ?>
        </div>

        <div class="events">
            <h1 class="event-list">Event List</h1>
            <table>
                <tr>
                    <th></th>
                    <th>Event Name</th>
                    <th>Picture</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td></td>
                        <td><?php echo $row['eventName']; ?></td>
                        <td><img src="<?php echo $row['eventPicture']; ?>" alt="Event Picture" style="width: 100px;"></td>
                        <td><?php echo $row['eventDate']; ?></td>
                        <td><?php echo date("g:iA", strtotime($row['startTime'])); ?> to <?php echo date("g:iA", strtotime($row['endTime'])); ?> </td>
                        <td><?php echo $row['eventDescription']; ?></td>
                        <td><?php echo $row['eventLocation']; ?></td>
                        <td><button class="edit" onclick="location.href='edit_event.php?id=<?php echo $row['event_id']; ?>'">Edit</button></td>
                        <td>
                            <form method="post" action="delete_event.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                <button type="submit" class="deleteBtn">Delete</button>
                            </form>
                        </td>
                        <td><?php echo $row['approvalStatus']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
       
    </div>
   

    <script src="../js/dropdown-menu.js"></script>



     
   
</body>
</html>